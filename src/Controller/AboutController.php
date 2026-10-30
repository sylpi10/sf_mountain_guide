<?php

namespace App\Controller;

use App\Repository\AboutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutController extends AbstractController
{
    #[Route(path: ['fr' => '/about', 'en' => '/en/about'], name: 'about')]
    public function about(AboutRepository $aboutRepo): Response
    {
        // $about = $aboutRepo->findOneById(1);
        $about = $aboutRepo->findOneAbout();
        $displayBtn = true;

        return $this->render('about/about.html.twig', [
            'about' => $about,
            'displayBtn' => $displayBtn,
        ]);
    }
}
