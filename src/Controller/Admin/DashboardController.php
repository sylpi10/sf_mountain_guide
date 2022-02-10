<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\User;
use App\Entity\About;
use App\Entity\Contact;
use App\Entity\Discipline;
use App\Entity\NewsLetter;
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
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Disciplines', 'fas fa-skiing', Discipline::class);
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Blog', 'fas fa-newspaper', Blog::class);
        yield MenuItem::linkToCrud('About', 'fas fa-address-card', About::class);
        yield MenuItem::linkToCrud('Newsletter', 'far fa-paper-plane', NewsLetter::class);
        yield MenuItem::linkToCrud('Mails reçus', 'fas fa-envelope-open-text', Contact::class);
    }
    // public function configureCrud(): Crud
    // {
    //     return Crud::new()
    //         // add your form theme here
    //         ->addFormTheme('@BundleName/Form/wysiwyg_widget.html.twig');
    // }
}
