<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DisciplinesController extends AbstractController
{
    /**
     * @Route("/disciplines", name="disciplines")
     */
    public function index()
    {
        return $this->render('disciplines/index.html.twig', []);
    }
}
