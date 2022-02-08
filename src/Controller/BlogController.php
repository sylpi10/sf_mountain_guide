<?php

namespace App\Controller;

use App\Repository\BlogRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    private PaginatorInterface $paginator;
    private BlogRepository $blogRepository;

    public function __construct(
        BlogRepository $blogRepository,
        PaginatorInterface $paginator
    ) {
        $this->paginator = $paginator;
        $this->blogRepository = $blogRepository;
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function index(Request $request)
    {
        // $articles = $blogRepository->findAll();
        $displayBtn = true;
        $articles = $this->paginator->paginate(
            $this->blogRepository->findByDate(),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
            "displayBtn" => $displayBtn
        ]);
    }
}
