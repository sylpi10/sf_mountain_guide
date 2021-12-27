<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\DisciplineRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class GlobalController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        Request $request,
        MailerInterface $mailer,
        TranslatorInterface $translator,
        DisciplineRepository $disciplineRepo
    ) {
        //  = new Contact();
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);
        $disciplines = $disciplineRepo->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            if (!empty($_POST['website'])) {
                return $this->redirectToRoute("home");
            } else {
                $email = (new TemplatedEmail())
                    ->from($contact->get('email')->getData())
                    ->to("contact@directicimes.com")
                    // ->to("syl.pillet@hotmail.fr")
                    ->subject("Nouveau Message depuis DirectiCimes")
                    // ->htmlTemplate("global/index.html.twig")
                    ->text($contact->get('message')->getData())
                    ->context([
                        "form" => $form->createView(),
                    ]);

                $mailer->send($email);

                // $notification->notify($contact);
                $message = $translator->trans("Your email has been send");

                $this->addFlash('success', $message);
                // return $this->redirect(
                //     $this->generateUrl('home') . '#top'
                // );
                return $this->redirectToRoute("home");
            }
        }

        return $this->render('global/index.html.twig', [
            'form' => $form->createView(),
            "disciplines" => $disciplines,
            "displayBtn" => true
        ]);
    }



    //   /**
    //  * @Route("/register", name="register")
    //  */
    // public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) 
    // {
    //     $user = new User();

    //     $form = $this->createForm(RegisterType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $passWrdCrypt = $encoder->encodePassword($user, $user->getPassword());
    //         $user->setPassword($passWrdCrypt);
    //         $user->setRoles("ROLE_ADMIN");
    //        $manager->persist($user);
    //        $manager->flush();
    //     }

    //     return $this->render('global/register.html.twig', [
    //         'user' => $user,
    //         'form' => $form->createView()
    //     ]);
    // }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $util)
    {
        return $this->render('global/login.html.twig', [
            "lastUserName" => $util->getLastUsername(),
            "error" => $util->getLastAuthenticationError(),
            "displayBtn" => false
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/change-locale/{locale}", name="change-locale")
     */
    public function changeLocale($locale, Request $request)
    {
        //stock lang in session
        $request->getSession()->set('_locale', $locale);

        //back to previous page
        return $this->redirect($request->headers->get('referer'));
    }
}
