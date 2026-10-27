<?php

namespace App\Controller;

use App\Entity\AreaPage;
use App\Repository\AreaPageRepository;
use App\Repository\DisciplineRepository;
use App\Service\ContactFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AreaPageController extends AbstractController
{
    #[Route(path: ['fr' => '/{slug}', 'en' => '/en/{slug}'], name: 'area_page', requirements: ['slug' => AreaPage::SLUG_REQUIREMENT])]
    public function show(string $slug, Request $request, AreaPageRepository $areaPageRepo, DisciplineRepository $disciplineRepo, ContactFormHandler $contactForm): Response
    {
        $page = $areaPageRepo->findPublishedBySlug(AreaPage::frenchSlug($slug));

        if (!$page) {
            throw $this->createNotFoundException();
        }

        // slug d'une autre langue (/en/guide-montgenevre…) : vers l'URL de la langue demandée
        $locale = $request->getLocale();
        if ($page->getSlug($locale) !== $slug) {
            return $this->redirectToRoute('area_page', ['slug' => $page->getSlug($locale)], 301);
        }
        $request->attributes->set('_locale_slugs', ['fr' => $page->getSlug('fr'), 'en' => $page->getSlug('en')]);

        $contact = $contactForm->handle($request, $this->generateUrl('area_page', ['slug' => $slug, '_fragment' => 'contact-form']));
        if ($contact instanceof RedirectResponse) {
            return $contact;
        }

        return $this->render('area/show.html.twig', [
            'page' => $page,
            'disciplines' => $disciplineRepo->findAll(),
            'displayBtn' => true,
        ] + $contact);
    }
}
