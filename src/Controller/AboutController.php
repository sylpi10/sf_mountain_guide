<?php

namespace App\Controller;

use App\Repository\AboutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AboutController extends AbstractController
{
    /**
     * @Route("/about", name="about")
     *
     * @param AboutRepository $aboutRepo
     * @return Response
     */
    public function about(AboutRepository $aboutRepo): Response
    {
        $about = $aboutRepo->findOneById(1);
        $displayBtn = true;

        return $this->render('about/about.html.twig', [
            'about' => $about,
            "displayBtn" => $displayBtn
        ]);
    }
}
