<?php

namespace App\Controller;

use App\Repository\DisciplineRepository;
use App\Repository\PricingPageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PricingController extends AbstractController
{
    #[Route(path: '/tarifs', name: 'pricing')]
    public function index(PricingPageRepository $pricingRepo, DisciplineRepository $disciplineRepo): Response
    {
        return $this->render('pricing/index.html.twig', [
            'pricing' => $pricingRepo->findPage(),
            'disciplines' => $disciplineRepo->findAll(),
            'displayBtn' => true,
        ]);
    }
}
