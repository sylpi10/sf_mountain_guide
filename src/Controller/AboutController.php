<?php

namespace App\Controller;

use App\Repository\AboutRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
    /**
     * @Route("/about", name="about")
     */
    public function about(
        AboutRepository $aboutRepo
    ) {
        $about = $aboutRepo->findOneById(1);
        $displayBtn = true;

        return $this->render('about/about.html.twig', [
            // 'form' => $form->createView(),
            'about' => $about,
            "displayBtn" => $displayBtn
        ]);
    }
}
