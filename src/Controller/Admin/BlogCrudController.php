<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
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
            // ImageField::new('image')
            //     ->setBasePath('uploads')
            //     ->setUploadDir('public/uploads')
            //     ->setUploadedFileNamePattern('[randomhash].[extension]')
            //     ->setRequired(false)
            TextField::new('imageFile')
                ->setFormType(VichImageType::class)
        ];
    }
}
