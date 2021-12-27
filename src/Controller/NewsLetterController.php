<?php

namespace App\Controller;

use App\Entity\NewsLetter;
use App\Form\NewsLetterType;
use App\Repository\NewsLetterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsLetterController extends AbstractController
{
    /**
     * @Route("/newsletter", name="newsletter")
     */
    public function index(
        Request $request,
        MailerInterface $mailer,
        NewsLetterRepository $newsLetterRepository,
        EntityManagerInterface $manager,
        TranslatorInterface $translator
    ) {

        $formNews = $this->createForm(NewsLetterType::class);
        $newsletter = $formNews->handleRequest($request);
        $newsletterEntity = $newsLetterRepository->findAll();
        $displayBtn = false;
        if ($formNews->isSubmitted() && $formNews->isValid()) {

            // push subscriber to db
            $subscriber = new NewsLetter();
            $subscriber->setEmail($newsletter->get('email')->getData());
            $subscriber->setFullname($newsletter->get('fullname')->getData());

            $manager->persist($subscriber);
            $manager->flush();

            // send email to client who registered 
            $emailToClient = (new TemplatedEmail())
                ->from("contact@directicimes.com")
                // ->from("syl.pillet@hotmail.fr")
                ->to($newsletter->get('email')->getData())
                ->subject("Votre abonnement à la newsletter")
                ->text(
                    " Bonjour " . $newsletter->get('fullname')->getData() . ".\n Nous avons bien pris en compte votre abonnement à notre newsletter !"
                )
                ->context([
                    "formNews" => $formNews->createView(),
                ]);


            $mailer->send($emailToClient);


            $email = (new TemplatedEmail())
                ->from($newsletter->get('email')->getData())
                ->to("syl.pillet@hotmail.fr")
                // ->to("contact@directicimes.com")
                ->subject("Nouvel abonné à la newsletter")
                // ->htmlTemplate("global/index.html.twig")

                ->text(
                    $newsletter->get('fullname')->getData() . " s'est abonné à la newsletter. \nSon email est : " . $newsletter->get('email')->getData() . ' !'
                )
                ->context([
                    "formNews" => $formNews->createView(),
                ]);

            //send mail to admin
            $mailer->send($email);


            // confirmation msg
            $message = $translator->trans("You have been registered to our newsletter");
            $this->addFlash('success', $message);

            // redirection
            return $this->redirectToRoute("home");
        }


        return $this->render('news_letter/newsLetter.html.twig', [
            "form" => $formNews->createView(),
            "newsletterEntity" => $newsletterEntity,
            "displayBtn" => $displayBtn
        ]);
    }
}
