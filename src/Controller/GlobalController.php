<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Entity\Discipline;
use App\Form\RegisterType;
use App\Repository\DisciplineRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Notification\ContactNotification;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
            return $this->redirectToRoute("home");
        }

        return $this->render('global/index.html.twig', [
            'form' => $form->createView(),
            "disciplines" => $disciplines
        ]);
    }

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
                'id' => $id
            ]);
        }


        return $this->render('disciplines/detail.html.twig', [
            'discipline' => $discipline,
            'disciplines' => $disciplines,
            'form' => $form->createView(),
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
            "error" => $util->getLastAuthenticationError()
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
