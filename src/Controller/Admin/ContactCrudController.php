<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(["receivedAt" => "DESC"]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("name"),
            TextField::new("email"),
            TextEditorField::new("message"),
            DateTimeField::new("receivedAt", "Reçu le")->hideOnForm(),
        ];
    }
}
