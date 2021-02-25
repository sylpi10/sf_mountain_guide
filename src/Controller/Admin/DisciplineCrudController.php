<?php

namespace App\Controller\Admin;

use App\Entity\Discipline;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DisciplineCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Discipline::class;
    }


    public function configureFields(string $pageName): iterable
    {



        return [
            TextField::new('title'),
            TextField::new('englishTitle'),
            TextEditorField::new('content'),
            TextEditorField::new('englishContent'),
            // ImageField::new('infoImage')
            // ->setBasePath('uploads/')->setUploadDir('public/uploads')
            // ->setUploadedFileNamePattern('[randomhash].[extension]')
            // ->setRequired(false),
            // ImageField::new('bgImage')
            // ->setBasePath('uploads/')->setUploadDir('public/uploads')
            // ->setUploadedFileNamePattern('[randomhash].[extension]')
            // ->setRequired(false),
            TextEditorField::new('persNumber')->onlyOnForms(),
            TextEditorField::new('englishNbPers')->onlyOnForms(),
            TextField::new('duration')->onlyOnForms(),
            TextField::new('englishDuration')->onlyOnForms(),
            TextField::new('location')->onlyOnForms(),
            TextField::new('englishLocation')->onlyOnForms(),
            TextField::new('price')->onlyOnForms(),
            TextField::new('englishPrice')->onlyOnForms()
        ];
    }
}
