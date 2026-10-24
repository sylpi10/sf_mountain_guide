<?php

namespace App\Controller;

use App\Entity\NewsLetterSubscriber;
use App\Form\NewsLetterSubscriberType;
use App\Repository\NewsLetterSubscriberRepository;
use App\Service\FailureAlerter;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UriSigner;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NewsLetterSubscriberController extends AbstractController
{
    private MailerInterface $mailer;
    private NewsLetterSubscriberRepository $newsLetterRepository;
    private EntityManagerInterface $manager;
    private TranslatorInterface $translator;
    public function __construct(
        MailerInterface $mailer,
        NewsLetterSubscriberRepository $newsLetterRepository,
        EntityManagerInterface $manager,
        TranslatorInterface $translator,
        private LoggerInterface $logger,
        private FailureAlerter $alerter,
        private UriSigner $uriSigner,
        // expéditeur = le compte du MAILER_DSN (SPF/DMARC)
        private string $mailerFrom,
    ) {
        $this->mailer = $mailer;
        $this->newsLetterRepository = $newsLetterRepository;
        $this->manager = $manager;
        $this->translator = $translator;
    }
    #[Route(path: '/newsletter', name: 'newsletter')]
    public function index(Request $request)
    {
        $formNews = $this->createForm(NewsLetterSubscriberType::class);
        $formNews->handleRequest($request);
        $newsletterEntity = $this->newsLetterRepository->findAll();
        $displayBtn = false;
        if ($formNews->isSubmitted() && $formNews->isValid()) {
            $session = $request->getSession();
            $honeypot = trim((string) $request->request->get("website", ""));
            $token = (string) $request->request->get("newsletter_token", "");
            $formTime = (int) $request->request->get("newsletter_time", 0);
            $sessionToken = (string) $session->get("newsletter_form_token", "");
            $now = time();
            $isTooFast = $formTime > 0 ? ($now - $formTime) < 4 : true;
            $isTooOld = $formTime > 0 ? ($now - $formTime) > 86400 : true;
            $tokenInvalid = $token === "" || $token !== $sessionToken;

            if ($honeypot !== "" || $tokenInvalid || $isTooFast || $isTooOld) {
                return $this->redirectToRoute("home");
            }
            // l'abonné rempli par le formulaire (nom, email et case de consentement RGPD)
            /** @var NewsLetterSubscriber $subscriber */
            $subscriber = $formNews->getData();

            $this->manager->persist($subscriber);
            $this->manager->flush();

            $fullname = $subscriber->getFullname();
            $email = $subscriber->getEmail();
            $from = new Address($this->mailerFrom, "DirectiCimes");

            // mail de bienvenue à l'abonné, dans la langue du site, avec son lien de désabonnement
            $unsubscribeUrl = $this->unsubscribeUrl($subscriber);
            $welcome = new TemplatedEmail()
                ->from($from)
                ->to($email)
                ->replyTo("contact@directicimes.com")
                ->subject($this->translator->trans("Welcome to the Directicimes newsletter"))
                ->htmlTemplate("emails/newsletter_welcome.html.twig")
                ->textTemplate("emails/newsletter_welcome.txt.twig")
                ->context(["fullname" => $fullname, "unsubscribe_url" => $unsubscribeUrl]);
            // bouton « Se désabonner » affiché par Gmail, Outlook… (désabonnement en un clic, RFC 8058)
            $welcome->getHeaders()
                ->addTextHeader("List-Unsubscribe", "<" . $unsubscribeUrl . ">")
                ->addTextHeader("List-Unsubscribe-Post", "List-Unsubscribe=One-Click");
            $this->sendMail($welcome, $subscriber);

            // notification à l'admin
            $this->sendMail(
                new Email()
                    ->from($from)
                    ->to("contact@directicimes.com", "georgesyn@gmail.com")
                    ->subject("Nouvel abonné à la newsletter")
                    ->text($fullname . " s'est abonné à la newsletter. \nSon email est : " . $email . " !"),
                $subscriber,
            );

            // confirmation msg
            $message = $this->translator->trans(
                "You have been registered to our newsletter",
            );
            $this->addFlash("success", $message);

            // redirection
            return $this->redirectToRoute("newsletter");
        }

        [$newsletterToken, $newsletterTime] = $this->refreshNewsletterGuards($request);

        return $this->render("news_letter/newsLetter.html.twig", [
            "form" => $formNews,
            "newsletterEntity" => $newsletterEntity,
            "displayBtn" => $displayBtn,
            "newsletter_token" => $newsletterToken,
            "newsletter_time" => $newsletterTime,
        ]);
    }

    /**
     * Lien de désabonnement envoyé par mail. Signé avec APP_SECRET : impossible de fabriquer
     * le lien d'un autre abonné en changeant l'id.
     *
     * GET  : page de confirmation (les antivirus de messagerie ouvrent les liens, ils ne doivent pas désabonner)
     * POST : désabonnement, depuis le bouton de la page ou le bouton « Se désabonner » de la messagerie
     */
    #[Route(path: '/newsletter/desabonnement/{id}', name: 'newsletter_unsubscribe', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function unsubscribe(int $id, Request $request): Response
    {
        if (!$this->uriSigner->checkRequest($request)) {
            throw $this->createNotFoundException();
        }

        $subscriber = $this->newsLetterRepository->find($id);

        if ($subscriber && $request->isMethod('POST')) {
            // la même adresse a pu s'inscrire plusieurs fois : on retire toutes ses inscriptions
            foreach ($this->newsLetterRepository->findBy(["email" => $subscriber->getEmail()]) as $registration) {
                $this->manager->remove($registration);
            }
            $this->manager->flush();
            $this->logger->info("Désabonnement de la newsletter", ["subscriber_id" => $id]);

            // retour sur le même lien signé, qui affiche alors « vous êtes désabonné »
            return $this->redirect($request->getUri(), Response::HTTP_SEE_OTHER);
        }

        return $this->render("news_letter/unsubscribe.html.twig", [
            "subscriber" => $subscriber,
            "displayBtn" => false,
        ]);
    }

    private function unsubscribeUrl(NewsLetterSubscriber $subscriber): string
    {
        return $this->uriSigner->sign($this->generateUrl(
            "newsletter_unsubscribe",
            ["id" => $subscriber->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        ));
    }

    /**
     * L'abonné est déjà en base (consultable en admin) : un SMTP en panne ne doit pas faire une erreur 500 au visiteur.
     * Chaque mail est tenté séparément : l'échec de la confirmation n'empêche pas la notification à l'admin.
     */
    private function sendMail(Email $mail, NewsLetterSubscriber $subscriber): void
    {
        try {
            $this->mailer->send($mail);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error("Envoi d'un mail de newsletter impossible", [
                "subscriber_id" => $subscriber->getId(),
                "subject" => $mail->getSubject(),
                "exception" => $e,
            ]);
            $this->alerter->alert(
                "Directicimes : mail de newsletter non envoye",
                sprintf("Abonné n°%d enregistré, mail « %s » pas envoyé.", $subscriber->getId(), $mail->getSubject()),
                $e,
            );
        }
    }

    private function refreshNewsletterGuards(Request $request): array
    {
        $token = bin2hex(random_bytes(16));
        $time = time();

        $session = $request->getSession();
        $session->set("newsletter_form_token", $token);
        $session->set("newsletter_form_time", $time);

        return [$token, $time];
    }
}
