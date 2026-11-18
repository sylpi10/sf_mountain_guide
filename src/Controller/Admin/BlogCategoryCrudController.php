<?php

namespace App\Controller\Admin;

use App\Entity\BlogCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BlogCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BlogCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories du blog')
            ->setPageTitle('index', 'Catégories du blog')
            ->setHelp('index', 'Filtres affichés en haut de la page Blog. Une catégorie sans article n\'est pas affichée.')
            ->setDefaultSort(['position' => 'ASC', 'name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom')
                ->setHelp('Sert aussi à l\'adresse du filtre (/blog?categorie=…) : éviter de le changer une fois publié.'),
            TextField::new('englishName', 'Nom anglais')->setHelp('Facultatif : si vide, le nom français est affiché.'),
            IntegerField::new('position', 'Ordre')->setHelp('Les pastilles sont triées par ordre croissant.'),
        ];
    }
}
