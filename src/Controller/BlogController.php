<?php

namespace App\Controller;

use App\Repository\BlogRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(BlogRepository $blogRepository, PaginatorInterface $paginator, Request $request)
    {
        // $articles = $blogRepository->findAll();
        $articles = $paginator->paginate(
            $blogRepository->findByDate(),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('blog/index.html.twig', [
            'articles' => $articles
        ]);
    }
}
