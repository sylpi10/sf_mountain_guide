<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\DisciplineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class GlobalController extends AbstractController
{
    private MailerInterface $mailer;
    private TranslatorInterface $translator;
    private DisciplineRepository $disciplineRepo;
    private AuthenticationUtils $util;
    private EntityManagerInterface $manager;

    public function __construct(
        MailerInterface $mailer,
        TranslatorInterface $translator,
        DisciplineRepository $disciplineRepo,
        AuthenticationUtils $util,
        EntityManagerInterface $manager
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->disciplineRepo = $disciplineRepo;
        $this->util = $util;
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $sentEmail = new Contact();
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // honeypot for robots
            if (!empty($_POST['website'])) {
                return $this->redirectToRoute("home");
                // if not filled, handle request
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

                $sentEmail->setName($form->get('name')->getData());
                $sentEmail->setEmail($form->get('email')->getData());
                $sentEmail->setMessage($form->get('message')->getData());

                // persist contact infos
                $this->manager->persist($sentEmail);
                $this->manager->flush();

                $this->mailer->send($email);

                /**
                 * TODO: send email confirmation to client
                 */

                // $notification->notify($contact);
                $message = $this->translator->trans("Your email has been send");

                $this->addFlash('success', $message);
                return $this->redirect(
                    $this->generateUrl('home') . '#top'
                );
                // return $this->redirectToRoute("home");
            }
        }

        // return every disciplines on home page
        $disciplines = $this->disciplineRepo->findAll();
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
    public function login()
    {
        return $this->render('global/login.html.twig', [
            "lastUserName" => $this->util->getLastUsername(),
            "error" => $this->util->getLastAuthenticationError(),
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
