<?php

namespace App\Controller;

use App\Entity\LegalPage;
use App\Repository\LegalPageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalPageController extends AbstractController
{
    #[Route(path: '/{slug}', name: 'legal_page', requirements: ['slug' => LegalPage::SLUG_REQUIREMENT])]
    public function show(string $slug, LegalPageRepository $legalPageRepo): Response
    {
        $page = $legalPageRepo->findPublishedBySlug($slug);

        if (!$page) {
            throw $this->createNotFoundException();
        }

        return $this->render('legal/show.html.twig', [
            'page' => $page,
            'displayBtn' => true,
        ]);
    }
}
