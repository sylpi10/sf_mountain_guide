<?php

namespace App\Controller\Admin;

use App\Entity\About;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AboutCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return About::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('whoTitle'),
            TextField::new('whoEnglishTitle'),
            TextEditorField::new('whoText'),
            TextEditorField::new('whoEnglishText'),
            TextField::new('whyTitle'),
            TextField::new('whyEnglishTitle')->onlyOnForms(),
            TextEditorField::new('whyText'),
            TextEditorField::new('whyEnglishText')->onlyOnForms()
        ];
    }
}
