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
        $session = $request->getSession();

        if ($form->isSubmitted() && $form->isValid()) {
            // honeypot for robots
            $honeypot = trim((string) $request->request->get("website", ""));
            $token = (string) $request->request->get("contact_token", "");
            $formTime = (int) $request->request->get("contact_time", 0);
            $sessionToken = (string) $session->get("contact_form_token", "");
            $now = time();
            $isTooFast = $formTime > 0 ? ($now - $formTime) < 4 : true;
            $isTooOld = $formTime > 0 ? ($now - $formTime) > 86400 : true;
            $tokenInvalid = $token === "" || $token !== $sessionToken;

            if ($honeypot !== "" || $tokenInvalid || $isTooFast || $isTooOld) {
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
                // return $this->redirect($this->generateUrl("home") . "#top");
                return $this->redirectToRoute("home");
            }
        }

        [$contactToken, $contactTime] = $this->refreshContactGuards($request);

        // return every disciplines on home page
        $disciplines = $this->disciplineRepo->findAll();
        return $this->render("global/index.html.twig", [
            "form" => $form->createView(),
            "disciplines" => $disciplines,
            "displayBtn" => true,
            "contact_token" => $contactToken,
            "contact_time" => $contactTime,
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

    private function refreshContactGuards(Request $request): array
    {
        $token = bin2hex(random_bytes(16));
        $time = time();

        $session = $request->getSession();
        $session->set("contact_form_token", $token);
        $session->set("contact_form_time", $time);

        return [$token, $time];
    }
}
