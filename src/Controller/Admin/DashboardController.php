<?php

namespace App\Controller\Admin;

// use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
// use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

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
        return Dashboard::new()->setTitle('Yoann Admin Space');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Mon Dashboard', 'fa fa-home');
        yield MenuItem::linkTo(DisciplineCrudController::class, 'Disciplines', 'fas fa-skiing');
        yield MenuItem::linkTo(UserCrudController::class, 'Utilisateurs', 'fas fa-user');
        yield MenuItem::linkTo(AboutCrudController::class, 'About', 'fas fa-address-card');
        yield MenuItem::subMenu('Tarifs', 'fas fa-euro-sign')->setSubItems([
            MenuItem::linkTo(PricingGroupCrudController::class, 'Blocs de tarifs'),
            MenuItem::linkTo(PricingSectionCrudController::class, 'Sections'),
            MenuItem::linkTo(PricingPageCrudController::class, 'Saison et introduction'),
        ]);
        yield MenuItem::linkTo(ReviewCrudController::class, 'Avis clients', 'fas fa-star');
        yield MenuItem::linkTo(LegalPageCrudController::class, 'CGV / pages légales', 'fas fa-file-contract');
        yield MenuItem::linkTo(AreaPageCrudController::class, 'Pages secteurs (SEO)', 'fas fa-map-marked-alt');
        yield MenuItem::linkTo(SiteImageCrudController::class, 'Images du site', 'fas fa-image');
        yield MenuItem::linkTo(SiteTextCrudController::class, 'Textes de présentation des activités', 'fas fa-align-left');
        yield MenuItem::linkTo(NewsLetterSubscriberCrudController::class, 'Abonnés à la Newsletter', 'far fa-paper-plane');
        yield MenuItem::linkTo(ContactCrudController::class, 'Mails reçus', 'fas fa-envelope-open-text');
        yield MenuItem::linkTo(ContactRecipientCrudController::class, 'Destinataires des mails', 'fas fa-at');
        yield MenuItem::linkTo(BlogCrudController::class, 'Blog', 'fas fa-newspaper');
        yield MenuItem::linkTo(CommentCrudController::class, 'Commentaires', 'fas fa-solid fa-comments');
    }
    // public function configureCrud(): Crud
    // {
    //     return Crud::new()
    //         // add your form theme here
    //         ->addFormTheme('@BundleName/Form/wysiwyg_widget.html.twig');
    // }
}
