<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class PhotosController extends AbstractController
{
    #[Route(path: '/photos', name: 'photos')]
    public function pictures(AlbumRepository $albumRepository, Request $request)
    {
        // $request->getSession()->set('_locale', $locale);
        $albums = $albumRepository->findAll();
        return $this->render("photos/photos.html.twig", [
            "albums" => $albums,
        ]);
    }
}
