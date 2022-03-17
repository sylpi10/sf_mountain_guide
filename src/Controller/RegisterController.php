<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private TranslatorInterface $translator;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        TranslatorInterface $translator,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->translator = $translator;
    }

    /**
     * @Route("/register", name="register")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $user = $form->getData();
            $passWrdCrypt = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($passWrdCrypt);
            $user->setRoles("ROLE_USER");
            $manager->persist($user);
            $manager->flush();

            $message = $this->translator->trans("You have been successfully registered");
            $this->addFlash('success', $message);
            return new RedirectResponse('/login');
        }


        return $this->render('global/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            "displayBtn" => true
        ]);
    }
}
