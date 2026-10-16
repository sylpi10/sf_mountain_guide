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
    /**
     *
     * @param [type] $id
     * @param Discipline $discipline
     * @param Request $request
     * @param string $slug
     * @return Response
     */
    #[Route(path: 'discipline/{slug}-{id}', name: 'detail', requirements: ['slug' => '[a-z0-9\-]*'])]
    public function detail(
        $id,
        Request $request,
        #[MapEntity(id: 'id')] Discipline $discipline,
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
