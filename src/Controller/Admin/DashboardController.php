<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\User;
use App\Entity\About;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\Discipline;
use App\Entity\NewsLetterSubscriber;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    private AdminUrlGenerator $adminUrlGenerator;
    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(DisciplineCrudController::class)
            // ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Yoann Admin Space');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Mon Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Disciplines', 'fas fa-skiing', Discipline::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('About', 'fas fa-address-card', About::class);
        yield MenuItem::linkToCrud('Abonnés à la Newsletter', 'far fa-paper-plane', NewsLetterSubscriber::class);
        yield MenuItem::linkToCrud('Mails reçus', 'fas fa-envelope-open-text', Contact::class);
        yield MenuItem::linkToCrud('Blog', 'fas fa-newspaper', Blog::class);
        yield MenuItem::linkToCrud('Commentaires', 'fas fa-solid fa-comments', Comment::class);
    }
    // public function configureCrud(): Crud
    // {
    //     return Crud::new()
    //         // add your form theme here
    //         ->addFormTheme('@BundleName/Form/wysiwyg_widget.html.twig');
    // }
}
