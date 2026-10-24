<?php

namespace App\Controller;

use App\Entity\NewsLetterSubscriber;
use App\Form\NewsLetterSubscriberType;
use App\Repository\NewsLetterSubscriberRepository;
use App\Service\FailureAlerter;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

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
        $newsletter = $formNews->handleRequest($request);
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
            // push subscriber to db
            $subscriber = new NewsLetterSubscriber();
            $subscriber->setEmail($newsletter->get("email")->getData());
            $subscriber->setFullname($newsletter->get("fullname")->getData());

            $this->manager->persist($subscriber);
            $this->manager->flush();

            $fullname = $newsletter->get("fullname")->getData();
            $email = $newsletter->get("email")->getData();
            $from = new Address($this->mailerFrom, "DirectiCimes");

            // confirmation à l'abonné
            $this->sendMail(
                new Email()
                    ->from($from)
                    ->to($email)
                    ->subject("Votre abonnement à la newsletter")
                    ->text(" Bonjour " . $fullname . ".\n Nous avons bien pris en compte votre abonnement à notre newsletter !"),
                $subscriber,
            );

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
