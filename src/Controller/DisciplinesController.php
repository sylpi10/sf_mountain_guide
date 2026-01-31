<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Entity\Discipline;
use App\Repository\DisciplineRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DisciplinesController extends AbstractController
{
    private DisciplineRepository $disciplineRepo;
    private MailerInterface $mailer;
    private TranslatorInterface $translator;

    public function __construct(
        DisciplineRepository $disciplineRepo,
        MailerInterface $mailer,
        TranslatorInterface $translator,
    ) {
        $this->disciplineRepo = $disciplineRepo;
        $this->mailer = $mailer;
        $this->translator = $translator;
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
        Request $request,
        Discipline $discipline,
        string $slug,
    ): Response {
        if ($discipline->getSlug() !== $slug) {
            return $this->redirectToRoute(
                "detail",
                [
                    "id" => $discipline->getId(),
                    "slug" => $discipline->getSlug(),
                ],
                301,
            );
        }
        $displayBtn = true;
        $disciplines = $this->disciplineRepo->findAll();
        // $discipline = $disciplineRepo->findOneById($id);
        $form = $this->createForm(ContactType::class);

        $contact = $form->handleRequest($request);
        $disciplines = $this->disciplineRepo->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $request->getSession();
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
            } else {
                $email = (new TemplatedEmail())->from($contact->get("email")->getData())->to("contact@directicimes.com", "georgesyn@gmail.com")->subject("Nouveau Message depuis DirectiCimes")
                    // ->htmlTemplate("global/index.html.twig")
                    ->text($contact->get("message")->getData())
                    ->context([
                        "form" => $form->createView(),
                    ]);

                $this->mailer->send($email);

                // $notification->notify($contact);
                $message = $this->translator->trans("Your email has been send");

                $this->addFlash("success", $message);
                return $this->redirectToRoute("detail", [
                    "id" => $id,
                    "slug" => $slug,
                ]);
            }
        }

        [$contactToken, $contactTime] = $this->refreshContactGuards($request);

        return $this->render("disciplines/detail.html.twig", [
            "discipline" => $discipline,
            "disciplines" => $disciplines,
            "form" => $form->createView(),
            "displayBtn" => $displayBtn,
            "contact_token" => $contactToken,
            "contact_time" => $contactTime,
        ]);
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
