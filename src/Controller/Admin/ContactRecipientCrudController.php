<?php

namespace App\Controller\Admin;

use App\Entity\ContactRecipient;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactRecipientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContactRecipient::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Destinataire')
            ->setEntityLabelInPlural('Destinataires')
            ->setPageTitle('index', 'Destinataires des messages du formulaire de contact')
            ->setHelp('index', 'Chaque message envoyé depuis le formulaire de contact est transmis à toutes les adresses actives. Si aucune adresse n\'est active, il part sur contact@directicimes.com.')
            ->setDefaultSort(['id' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email', 'Adresse e-mail'),
            TextField::new('name', 'Nom (facultatif)')->setHelp('Pour s\'y retrouver, par ex. « Yoann - Gmail ».'),
            BooleanField::new('isActive', 'Actif')->setHelp('Décocher pour arrêter temporairement les envois vers cette adresse.'),
        ];
    }
}
