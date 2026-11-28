<?php

namespace App\Controller\Admin;

use App\Entity\ActivityGroup;
use App\Enum\PricingSeason;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ActivityGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ActivityGroup::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Groupe d\'activités')
            ->setEntityLabelInPlural('Groupes d\'activités')
            ->setPageTitle('index', 'Groupes du menu Activités')
            ->setHelp('index', 'Colonnes du menu « Activités » et sections de la page Activités (Hiver, Été, Devenir autonome…). Un groupe sans discipline publiée n\'est pas affiché.')
            ->setDefaultSort(['position' => 'ASC', 'id' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom (FR)')->setHelp('Ex. : Hiver, Été, Devenir autonome en montagne.'),
            TextField::new('nameEn', 'Nom (EN)')->setHelp('Si vide, le nom FR est affiché.'),
            ChoiceField::new('season', 'Saison')
                ->setFormTypeOption('choice_label', fn (PricingSeason $season) => $season->label())
                ->formatValue(fn ($value, ActivityGroup $group) => $group->getSeason()?->label() ?? '—')
                ->setRequired(false)
                ->setHelp('Pendant cette saison (mois réglés dans Tarifs > Saison et introduction), le groupe passe en tête du menu et ses disciplines « mises en avant » s\'affichent sur l\'accueil. Laisser vide pour un groupe toute l\'année.'),
            IntegerField::new('position', 'Ordre')->setHelp('Plus petit = plus haut / plus à gauche.'),
            AssociationField::new('disciplines', 'Disciplines')->onlyOnIndex(),
        ];
    }
}
