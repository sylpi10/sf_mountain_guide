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
use Symfony\Component\Routing\Annotation\Route;

class AreaPageController extends AbstractController
{
    #[Route(path: '/{slug}', name: 'area_page', requirements: ['slug' => AreaPage::SLUG_REQUIREMENT])]
    public function show(string $slug, Request $request, AreaPageRepository $areaPageRepo, DisciplineRepository $disciplineRepo, ContactFormHandler $contactForm): Response
    {
        $page = $areaPageRepo->findPublishedBySlug($slug);

        if (!$page) {
            throw $this->createNotFoundException();
        }

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
