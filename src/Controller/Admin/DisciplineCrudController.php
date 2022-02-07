<?php

namespace App\Controller\Admin;

use App\Entity\Discipline;
use Doctrine\ORM\EntityManagerInterface;
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


    public function configureFields(string $pageName): iterable
    {

        return [
            TextField::new('title', 'Nom de discipline'),
            TextField::new('englishTitle', 'Nom de discipline en anglais'),
            TextEditorField::new('content', 'Description de l\'activité'),
            TextEditorField::new('englishContent', 'Description en anglais'),
            // ImageField::new('infoImage')
            // ->setBasePath('uploads/')->setUploadDir('public/uploads')
            // ->setUploadedFileNamePattern('[randomhash].[extension]')
            // ->setRequired(false),
            // ImageField::new('bgImage')
            // ->setBasePath('uploads/')->setUploadDir('public/uploads')
            // ->setUploadedFileNamePattern('[randomhash].[extension]')
            // ->setRequired(false),
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

        // if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
        //     $fields[] = ImageField::new('image')->setBasePath('uploads/');
        // } else {
        //     $fields[] = ImageField::new('imageFile')->setFormType(VichImageType::class);
        // }
    }
}
