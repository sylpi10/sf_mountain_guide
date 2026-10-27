<?php

namespace App\Controller;

use App\Entity\LegalPage;
use App\Repository\LegalPageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalPageController extends AbstractController
{
    #[Route(path: ['fr' => '/{slug}', 'en' => '/en/{slug}'], name: 'legal_page', requirements: ['slug' => LegalPage::SLUG_REQUIREMENT])]
    public function show(string $slug, Request $request, LegalPageRepository $legalPageRepo): Response
    {
        $page = $legalPageRepo->findPublishedBySlug(LegalPage::frenchSlug($slug));

        if (!$page) {
            throw $this->createNotFoundException();
        }

        // slug d'une autre langue (/en/cgv…) : vers l'URL de la langue demandée
        $locale = $request->getLocale();
        if ($page->getSlug($locale) !== $slug) {
            return $this->redirectToRoute('legal_page', ['slug' => $page->getSlug($locale)], 301);
        }
        $request->attributes->set('_locale_slugs', ['fr' => $page->getSlug('fr'), 'en' => $page->getSlug('en')]);

        return $this->render('legal/show.html.twig', [
            'page' => $page,
            'displayBtn' => true,
        ]);
    }
}
