<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\DisciplineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GlobalController extends AbstractController
{
    private MailerInterface $mailer;
    private TranslatorInterface $translator;
    private DisciplineRepository $disciplineRepo;
    private EntityManagerInterface $manager;

    public function __construct(
        MailerInterface $mailer,
        TranslatorInterface $translator,
        DisciplineRepository $disciplineRepo,
        EntityManagerInterface $manager,
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->disciplineRepo = $disciplineRepo;
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="home")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $sentEmail = new Contact();
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // honeypot for robots
            if (!empty($_POST["website"])) {
                return $this->redirectToRoute("home");
                // if not filled, handle request
            } else {
                $email = (new TemplatedEmail())
                    ->from($contact->get("email")->getData())
                    ->to("contact@directicimes.com", "georgesyn@gmail.com")
                    // ->to("syl.pillet@hotmail.fr")
                    ->subject("Nouveau Message depuis DirectiCimes")
                    // ->htmlTemplate("global/index.html.twig")
                    ->text($contact->get("message")->getData())
                    ->context([
                        "form" => $form->createView(),
                    ]);

                $sentEmail->setName($form->get("name")->getData());
                $sentEmail->setEmail($form->get("email")->getData());
                $sentEmail->setMessage($form->get("message")->getData());

                // persist contact infos
                $this->manager->persist($sentEmail);
                $this->manager->flush();

                $this->mailer->send($email);

                // $notification->notify($contact);
                $message = $this->translator->trans("Your email has been send");

                $this->addFlash("success", $message);
                return $this->redirect($this->generateUrl("home") . "#top");
                // return $this->redirectToRoute("home");
            }
        }

        // return every disciplines on home page
        $disciplines = $this->disciplineRepo->findAll();
        return $this->render("global/index.html.twig", [
            "form" => $form->createView(),
            "disciplines" => $disciplines,
            "displayBtn" => true,
        ]);
    }

    /**
     * @Route("/change-locale/{locale}", name="change-locale")
     * @param [type] $locale
     * @param Request $request
     */
    public function changeLocale($locale, Request $request)
    {
        //stock lang in session
        $request->getSession()->set("_locale", $locale);

        //back to previous page
        return $this->redirect($request->headers->get("referer"));
    }
}
