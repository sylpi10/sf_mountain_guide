<?php

namespace App\Controller;

use App\Entity\About;
use App\Form\ContactType;
use App\Repository\AboutRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
    /**
     * @Route("/about", name="about")
     */
    public function about(
        // Request $request,
        // MailerInterface $mailer,
        AboutRepository $aboutRepo
    ) {
        //  = new Contact();
        // $form = $this->createForm(ContactType::class);
        // $contact = $form->handleRequest($request);
        $about = $aboutRepo->findOneById(1);
        $displayBtn = true;
        // if ($form->isSubmitted() && $form->isValid()) {

        //     $email = (new TemplatedEmail())
        //         ->from($contact->get('email')->getData())
        //         // ->to("syl.pillet@hotmail.fr")
        //         ->to("contact@directicimes.com")
        //         ->subject("New Mail from Website")
        //         // ->htmlTemplate("global/index.html.twig")
        //         ->text($contact->get('message')->getData())
        //         ->context([
        //             "form" => $form->createView()
        //         ]);

        //     $mailer->send($email);

        // $notification->notify($contact);

        //     $this->addFlash('success', "Your email has been send !!");
        //     return $this->redirectToRoute("about");
        // }

        return $this->render('about/about.html.twig', [
            // 'form' => $form->createView(),
            'about' => $about,
            "displayBtn" => $displayBtn
        ]);
    }
}
