<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class BlogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Blog::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('englishTitle'),
            TextEditorField::new('content'),
            TextEditorField::new('englishContent'),
            TextField::new('location'),
            ImageField::new('image', 'Image')
                ->onlyOnIndex()
                ->setBasePath('/uploads'),
            TextField::new('imageFile')
                ->onlyOnForms()
                ->setFormType(VichImageType::class)
        ];
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Articles')
            ->setPageTitle('edit', 'Modifier Un Article')
            ->setPageTitle('new', 'Créer Un Article');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions

            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            // ...
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Ajouter un Article');
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
