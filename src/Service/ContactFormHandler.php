<?php

namespace App\Service;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRecipientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Formulaire de contact commun à toutes les pages qui l'incluent (contact.html.twig) :
 * anti-spam, envoi aux destinataires actifs de l'admin, enregistrement du message.
 */
class ContactFormHandler
{
    // si aucun destinataire actif en admin, pour ne jamais perdre un message
    private const FALLBACK_RECIPIENT = "contact@directicimes.com";

    public function __construct(
        private FormFactoryInterface $formFactory,
        private MailerInterface $mailer,
        private TranslatorInterface $translator,
        private EntityManagerInterface $manager,
        private ContactRecipientRepository $recipientRepo,
        private LoggerInterface $logger,
        private string $mailerFrom,
        private HttpClientInterface $httpClient,
        private string $ntfyTopic,
    ) {}

    /**
     * Traite la soumission éventuelle du formulaire.
     *
     * @param string $redirectUrl page sur laquelle revenir après envoi (avec l'ancre du formulaire)
     *
     * @return RedirectResponse|array{form: \Symfony\Component\Form\FormView, contact_token: string, contact_time: int}
     *         redirection si le formulaire a été soumis et valide, sinon les variables à passer au template
     */
    public function handle(
        Request $request,
        string $redirectUrl,
    ): RedirectResponse|array {
        $form = $this->formFactory->create(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // robot : on redirige sans rien envoyer ni lui signaler
            if (!$this->isSpam($request)) {
                $this->send($form, $request);
                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add(
                        "success",
                        $this->translator->trans("Your email has been send"),
                    );
            }

            return new RedirectResponse($redirectUrl);
        }

        [$token, $time] = $this->refreshGuards($request);

        return [
            "form" => $form->createView(),
            "contact_token" => $token,
            "contact_time" => $time,
        ];
    }

    private function isSpam(Request $request): bool
    {
        $honeypot = trim((string) $request->request->get("website", ""));
        $token = (string) $request->request->get("contact_token", "");
        $formTime = (int) $request->request->get("contact_time", 0);
        $sessionToken = (string) $request
            ->getSession()
            ->get("contact_form_token", "");
        $now = time();
        $isTooFast = $formTime > 0 ? $now - $formTime < 4 : true;
        $isTooOld = $formTime > 0 ? $now - $formTime > 86400 : true;
        $tokenInvalid = $token === "" || $token !== $sessionToken;

        return $honeypot !== "" || $tokenInvalid || $isTooFast || $isTooOld;
    }

    private function send(FormInterface $form, Request $request): void
    {
        $name = $form->get("name")->getData();
        $email = $form->get("email")->getData();
        $message = $form->get("message")->getData();

        // coordonnées du visiteur en tête du mail : le reply-to n'est pas affiché par toutes les messageries
        $body = sprintf(
            "Nom : %s\nEmail : %s\nPage : %s\n\n%s",
            $name,
            $email,
            $request->getUri(),
            $message,
        );

        $mail = new Email()
            // expéditeur = le compte du MAILER_DSN (SPF/DMARC), le visiteur en reply-to
            ->from(new Address($this->mailerFrom, "DirectiCimes"))
            ->replyTo(new Address($email, $name))
            ->to(
                ...$this->recipientRepo->findActiveEmails() ?: [
                    self::FALLBACK_RECIPIENT,
                ],
            )
            // ->to("syl.pillet@hotmail.fr", "m2cclcvn9p@tanpony.com")
            ->subject("Nouveau message de " . $name . " depuis DirectiCimes")
            ->text($body);

        $contact = new Contact();
        $contact->setName($name);
        $contact->setEmail($email);
        $contact->setMessage($message);

        // persist contact infos
        $this->manager->persist($contact);
        $this->manager->flush();

        // le message est déjà en base (consultable en admin) : un SMTP en panne ne doit pas faire une erreur 500 au visiteur
        try {
            $this->mailer->send($mail);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error("Envoi du mail de contact impossible", [
                "contact_id" => $contact->getId(),
                "exception" => $e,
            ]);
            $this->alertFailure($contact, $e);
        }
    }

    /**
     * Alerte push (ntfy) quand le mail ne part pas : une alerte par mail serait perdue avec lui.
     * Pas de données du visiteur dans l'alerte, elle transite par un service tiers.
     */
    private function alertFailure(Contact $contact, \Throwable $e): void
    {
        if ($this->ntfyTopic === "") {
            return;
        }

        try {
            $this->httpClient->request("POST", "https://ntfy.sh/" . $this->ntfyTopic, [
                "headers" => [
                    "Title" => "Directicimes : mail de contact non envoye",
                    "Priority" => "high",
                    "Tags" => "warning",
                ],
                "body" => sprintf(
                    "Message n°%d enregistré mais pas envoyé (consultable dans l'admin).\n%s",
                    $contact->getId(),
                    mb_substr($e->getMessage(), 0, 300),
                ),
                "timeout" => 5,
            ])->getStatusCode();
        } catch (\Throwable $alertError) {
            $this->logger->error("Alerte ntfy impossible", ["exception" => $alertError]);
        }
    }

    private function refreshGuards(Request $request): array
    {
        $token = bin2hex(random_bytes(16));
        $time = time();

        $session = $request->getSession();
        $session->set("contact_form_token", $token);
        $session->set("contact_form_time", $time);

        return [$token, $time];
    }
}
