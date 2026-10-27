<?php

namespace App\Controller;

use App\Entity\Discipline;
use App\Repository\DisciplineRepository;
use App\Service\ContactFormHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Routing\Attribute\Route;

class DisciplinesController extends AbstractController
{
    public function __construct(
        private DisciplineRepository $disciplineRepo,
        private ContactFormHandler $contactForm,
    ) {
    }

    #[Route(path: ['fr' => '/activites', 'en' => '/en/activities'], name: 'activities')]
    public function index(Request $request): Response
    {
        $contact = $this->contactForm->handle(
            $request,
            $this->generateUrl('activities', ['_fragment' => 'contact-form']),
        );
        if ($contact instanceof RedirectResponse) {
            return $contact;
        }

        return $this->render('disciplines/index.html.twig', [
            'disciplines' => $this->disciplineRepo->findAll(),
        ] + $contact);
    }

    /**
     *
     * @param [type] $id
     * @param Discipline $discipline
     * @param Request $request
     * @param string $slug
     * @return Response
     */
    #[Route(path: ['fr' => '/discipline/{slug}-{id}', 'en' => '/en/activity/{slug}-{id}'], name: 'detail', requirements: ['slug' => '[a-z0-9\-]*'])]
    public function detail(
        $id,
        Request $request,
        #[MapEntity(id: 'id')] Discipline $discipline,
        string $slug,
    ): Response {
        $locale = $request->getLocale();
        if ($discipline->getSlug($locale) !== $slug) {
            return $this->redirectToRoute(
                "detail",
                [
                    "id" => $discipline->getId(),
                    "slug" => $discipline->getSlug($locale),
                ],
                301,
            );
        }
        // slugs des autres langues, pour le sélecteur de langue et les hreflang
        $request->attributes->set("_locale_slugs", [
            "fr" => $discipline->getSlug("fr"),
            "en" => $discipline->getSlug("en"),
        ]);
        $contact = $this->contactForm->handle(
            $request,
            $this->generateUrl("detail", [
                "id" => $id,
                "slug" => $slug,
                "_fragment" => "contact-form",
            ]),
        );
        if ($contact instanceof RedirectResponse) {
            return $contact;
        }

        return $this->render("disciplines/detail.html.twig", [
            "discipline" => $discipline,
            "disciplines" => $this->disciplineRepo->findAll(),
            "displayBtn" => true,
        ] + $contact);
    }
}
