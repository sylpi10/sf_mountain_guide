<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AboutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AboutController extends AbstractController
{
    /**
     *
     * @param AboutRepository $aboutRepo
     * @return Response
     */
    #[Route(path: '/about', name: 'about')]
    public function about(AboutRepository $aboutRepo): Response
    {
        // $about = $aboutRepo->findOneById(1);
        $about = $aboutRepo->findOneAbout();
        $displayBtn = true;

        return $this->render("about/about.html.twig", [
            "about" => $about,
            "displayBtn" => $displayBtn,
        ]);
    }
}
