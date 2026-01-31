<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\BlogRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlogController extends AbstractController
{
    private PaginatorInterface $paginator;
    private BlogRepository $blogRepository;
    private EntityManagerInterface $manager;
    private CommentRepository $commentRepo;
    private Security $security;
    private TranslatorInterface $translator;

    public function __construct(
        BlogRepository $blogRepository,
        PaginatorInterface $paginator,
        EntityManagerInterface $manager,
        CommentRepository $commentRepo,
        Security $security,
        TranslatorInterface $translator
    ) {
        $this->paginator = $paginator;
        $this->blogRepository = $blogRepository;
        $this->manager = $manager;
        $this->commentRepo = $commentRepo;
        $this->security = $security;
        $this->translator = $translator;
    }

    /**
     * @Route("/blog", name="blog")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $displayBtn = true;
        $articles = $this->paginator->paginate(
            $this->blogRepository->findByDate(),
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
            "displayBtn" => $displayBtn,
        ]);
    }

    /**
     * @Route("/blog/{slug}-{id}", name="blog_detail", requirements={"slug": "[a-z0-9\-]*"})
     *
     * @param Blog $post
     * @param Request $request
     * @return Response
     */
    public function detail(Blog $post, Request $request): Response
    {
        $comment = new Comment();
        $comment->setPost($post);
        // if ($this->isGranted('ROLE_USER')) {
        $comment->setAuthor($this->security->getUser());
        // }
        $comment->setPostedAt(new \DateTimeImmutable());
        $comment->setIsAccepted(false);
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($comment);
            $this->manager->flush();
            $message = $this->translator->trans("Your comment has been submitted, it will be displayed when the administartor validates it");
            $this->addFlash('success', $message);
            //without redirection, com will be re-posted on each reload (f5) 
            return $this->redirectToRoute("blog_detail", ["id" => $post->getId(), 'slug' => $post->getSlug()]);
        }

        $acceptedComments = $this->commentRepo->findByIsAccepted($post->getId());
        return $this->render('blog/details.html.twig', [
            'article' => $post,
            'form' => $form->createView(),
            'displayBtn' => true,
            'acceptedComments' => $acceptedComments
        ]);
    }
}
