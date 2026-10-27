<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Avis')
            ->setEntityLabelInPlural('Avis clients')
            ->setPageTitle('index', 'Avis clients')
            ->setHelp('index', 'Avis recopiés depuis la fiche Google, affichés dans le slider au-dessus du formulaire de contact (les plus récents d\'abord).')
            ->setDefaultSort(['reviewedAt' => 'DESC', 'id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('authorName', 'Auteur')->setHelp('Tel qu\'affiché sur Google (ex. : Marie D.).'),
            ChoiceField::new('rating', 'Note')
                ->setChoices(['★★★★★' => 5, '★★★★' => 4, '★★★' => 3, '★★' => 2, '★' => 1]),
            DateField::new('reviewedAt', 'Date de l\'avis')
                ->setHelp('Google affiche « il y a 3 mois » : une date approximative suffit, seuls le mois et l\'année sont affichés.'),
            TextareaField::new('content', 'Texte de l\'avis')
                ->setHelp('Texte d\'origine, copié-collé depuis Google.')
                ->setNumOfRows(6)
                ->onlyOnForms(),
            TextareaField::new('contentEn', 'Traduction anglaise')
                ->setHelp('Facultatif : affichée sur le site anglais. Si vide, le texte d\'origine est affiché.')
                ->setNumOfRows(6)
                ->onlyOnForms(),
            TextField::new('content', 'Texte')->setMaxLength(80)->onlyOnIndex(),
            BooleanField::new('published', 'Publié'),
        ];
    }
}
