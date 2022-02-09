<?php

namespace App\Controller\Admin;

use App\Entity\Discipline;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Vich\UploaderBundle\Form\Type\VichImageType;

class DisciplineCrudController extends AbstractCrudController
{
    // private $adminUrlGenerator;

    // public function __construct(EntityManagerInterface $entityManager, CrudUrlGenerator $crudUrlGenerator, AdminUrlGenerator $adminUrlGenerator)
    // {
    //     $this->entityManager = $entityManager;
    //     $this->crudUrlGenerator = $crudUrlGenerator;
    //     $this->adminUrlGenerator = $adminUrlGenerator;
    // }

    public static function getEntityFqcn(): string
    {
        return Discipline::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Disciplines - Activités')
            ->setPageTitle('edit', 'Modifier une discipline')
            ->setPageTitle('new', 'Ajouter Un Discipline');
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            TextField::new('title', 'Nom de discipline'),
            TextField::new('englishTitle', 'Nom de discipline en anglais'),
            TextEditorField::new('content', 'Description de l\'activité'),
            TextEditorField::new('englishContent', 'Description en anglais'),
            TextEditorField::new('persNumber', 'Nombre de participants')->onlyOnForms(),
            TextEditorField::new('englishNbPers', 'Nombre de participants en anglais')->onlyOnForms(),
            TextField::new('duration', 'Durée')->onlyOnForms(),
            TextField::new('englishDuration', 'Durée en anglais')->onlyOnForms(),
            TextField::new('location', 'Lieu de l\'activité')->onlyOnForms(),
            TextField::new('englishLocation', 'Lieu de l\'activité en anglais')->onlyOnForms(),
            TextField::new('price', 'Prix')->onlyOnForms(),
            TextField::new('englishPrice', 'Prix en anglais')->onlyOnForms(),
            ImageField::new('image', 'Image pour la liste')
                ->onlyOnIndex()
                ->setBasePath('/uploads'),
            TextField::new('imageFile', 'Image pour la liste')
                ->onlyOnForms()
                ->setFormType(VichImageType::class),
            ImageField::new('image', 'Image de fond')
                ->onlyOnIndex()
                ->setBasePath('/uploads'),
            TextField::new('imageDetailFile', 'image de fond de la page de détails')
                ->onlyOnForms()
                ->setFormType(VichImageType::class)

        ];
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions

            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            // ...
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Ajouter une Discipline');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-pencil-alt')->setLabel('Modifier');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, function (Action $action) {
                return $action->setIcon('fa fa-pencil-alt')->setLabel('Sauver et continuer l\'édition');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setIcon('fa fa-check')->setLabel('Enregister');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fa fa-eye')->setLabel('Détails');
            })

            ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')->setLabel('Supprimer');
            })
            ->update(Crud::PAGE_DETAIL, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-pencil')->setLabel('Modifier');
            })

            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-times')->setLabel('Supprimer');
            })

            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Sauver et Créer un autre');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setIcon('fa fa-check')->setLabel('Valider');
            })
            // in PHP 7.4 and newer you can use arrow functions
            // ->update(Crud::PAGE_INDEX, Action::NEW,
            //     fn (Action $action) => $action->setIcon('fa fa-file-alt')->setLabel(false))
        ;
    }
}
