<?php

namespace App\Controller;

use App\Repository\DisciplineRepository;
use App\Service\ContactFormHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GlobalController extends AbstractController
{
    public function __construct(
        private DisciplineRepository $disciplineRepo,
        private ContactFormHandler $contactForm,
    ) {
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route(path: "/", name: "home")]
    public function index(Request $request): Response
    {
        $contact = $this->contactForm->handle(
            $request,
            $this->generateUrl("home", ["_fragment" => "contact-form"]),
        );
        if ($contact instanceof RedirectResponse) {
            return $contact;
        }

        // return every disciplines on home page
        $disciplines = $this->disciplineRepo->findAll();
        return $this->render("global/index.html.twig", [
            "disciplines" => $disciplines,
            "displayBtn" => true,
        ] + $contact);
    }

    /**
     * @param [type] $locale
     * @param Request $request
     */
    #[
        Route(
            path: "/change-locale/{locale}",
            name: "change-locale",
            requirements: ["locale" => "en|fr"],
        ),
    ]
    public function changeLocale($locale, Request $request)
    {
        //stock lang in session
        $request->getSession()->set("_locale", $locale);

        //back to previous page, or home if no referer (e.g. direct/crawler request)
        return $this->redirect(
            $request->headers->get("referer") ?: $this->generateUrl("home"),
        );
    }
}
