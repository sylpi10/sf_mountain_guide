<?php

namespace App\Controller;

use App\Entity\AreaPage;
use App\Repository\AreaPageRepository;
use App\Repository\DisciplineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AreaPageController extends AbstractController
{
    #[Route(path: '/{slug}', name: 'area_page', requirements: ['slug' => AreaPage::SLUG_REQUIREMENT])]
    public function show(string $slug, AreaPageRepository $areaPageRepo, DisciplineRepository $disciplineRepo): Response
    {
        $page = $areaPageRepo->findPublishedBySlug($slug);

        if (!$page) {
            throw $this->createNotFoundException();
        }

        return $this->render('area/show.html.twig', [
            'page' => $page,
            'disciplines' => $disciplineRepo->findAll(),
            'displayBtn' => true,
        ]);
    }
}
