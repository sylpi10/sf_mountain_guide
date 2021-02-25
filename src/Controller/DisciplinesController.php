<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Entity\Discipline;
use App\Repository\DisciplineRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DisciplinesController extends AbstractController
{

    /**
     * @Route("discipline/{slug}-{id}", name="detail", requirements={"slug": "[a-z0-9\-]*"})
     *
     * @param [type] $id
     * @param DisciplineRepository $disciplineRepo
     * @param Discipline $discipline
     * @param MailerInterface $mailer
     * @param TranslatorInterface $translator
     * @param Request $request
     * @param string $slug
     * @return Response
     */
    public function detail(
        $id,
        DisciplineRepository $disciplineRepo,
        Discipline $discipline,
        MailerInterface $mailer,
        TranslatorInterface $translator,
        Request $request,
        string $slug
    ): Response {

        if ($discipline->getSlug() !== $slug) {
            return $this->redirectToRoute('detail', [
                'id' => $discipline->getId(),
                'slug' => $discipline->getSlug()
            ], 301);
        }

        $disciplines = $disciplineRepo->findAll();
        // $discipline = $disciplineRepo->findOneById($id);
        $form = $this->createForm(ContactType::class);


        $contact = $form->handleRequest($request);
        $disciplines = $disciplineRepo->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                // ->to("georgesyn@gmail.com")
                ->to("contact@directicimes.com")
                ->subject("Nouveau Message depuis DirectiCimes")
                // ->htmlTemplate("global/index.html.twig")
                ->text($contact->get('message')->getData())
                ->context([
                    "form" => $form->createView()
                ]);

            $mailer->send($email);

            // $notification->notify($contact);
            $message = $translator->trans("Your email has been send");

            $this->addFlash('success', $message);
            return $this->redirectToRoute("detail", [
                'id' => $id,
                'slug' => $slug
            ]);
        }

        return $this->render('disciplines/detail.html.twig', [
            'discipline' => $discipline,
            'disciplines' => $disciplines,
            'form' => $form->createView(),
        ]);
    }
}
