<?php

namespace App\Controller;

use App\Entity\NewsLetterSubscriber;
use App\Form\NewsLetterSubscriberType;
use App\Repository\NewsLetterSubscriberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
    ) {
        $this->mailer = $mailer;
        $this->newsLetterRepository = $newsLetterRepository;
        $this->manager = $manager;
        $this->translator = $translator;
    }
    /**
     * @Route("/newsletter", name="newsletter")
     */
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

            // send email to client who registered
            $emailToClient = (new TemplatedEmail())->from("contact@directicimes.com")
                // ->from("syl.pillet@hotmail.fr")
                ->to($newsletter->get("email")->getData())
                ->subject("Votre abonnement à la newsletter")
                ->text(
                    " Bonjour " .
                        $newsletter->get("fullname")->getData() .
                        ".\n Nous avons bien pris en compte votre abonnement à notre newsletter !",
                )
                ->context([
                    "formNews" => $formNews->createView(),
                ]);

            $this->mailer->send($emailToClient);

            $email = (new TemplatedEmail())
                // ->from($newsletter->get('email')->getData())
                ->from("contact@directicimes.com")
                // ->to("syl.pillet@hotmail.fr")
                ->to("contact@directicimes.com", "georgesyn@gmail.com")
                ->subject("Nouvel abonné à la newsletter")
                // ->htmlTemplate("global/index.html.twig")

                ->text(
                    $newsletter->get("fullname")->getData() .
                        " s'est abonné à la newsletter. \nSon email est : " .
                        $newsletter->get("email")->getData() .
                        " !",
                )
                ->context([
                    "formNews" => $formNews->createView(),
                ]);

            //send mail to admin
            $this->mailer->send($email);

            // confirmation msg
            $message = $this->translator->trans(
                "You have been registered to our newsletter",
            );
            $this->addFlash("success", $message);

            // redirection
            return $this->redirectToRoute("new");
        }

        [$newsletterToken, $newsletterTime] = $this->refreshNewsletterGuards($request);

        return $this->render("news_letter/newsLetter.html.twig", [
            "form" => $formNews->createView(),
            "newsletterEntity" => $newsletterEntity,
            "displayBtn" => $displayBtn,
            "newsletter_token" => $newsletterToken,
            "newsletter_time" => $newsletterTime,
        ]);
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
