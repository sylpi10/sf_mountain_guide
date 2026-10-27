<?php

namespace App\Controller\Admin;

use App\Entity\PricingSection;
use App\Enum\PricingSeason;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PricingSectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PricingSection::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Section tarifs')
            ->setEntityLabelInPlural('Sections tarifs')
            ->setPageTitle('index', 'Tarifs : sections')
            ->setHelp('index', 'Grandes parties de la page tarifs, rangées dans l\'onglet Hiver ou Été (Massif du Mont Blanc, Grandes voies…). Les tarifs eux-mêmes se saisissent dans « Blocs de tarifs ».')
            ->setDefaultSort(['season' => 'DESC', 'position' => 'ASC', 'id' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ChoiceField::new('season', 'Onglet')
                ->setFormTypeOption('choice_label', fn (PricingSeason $s) => $s->label())
                ->formatValue(fn ($value, PricingSection $section) => $section->getSeason()?->label()),
            TextField::new('title', 'Titre (FR)')->setHelp('Facultatif : inutile pour une section seule dans son onglet.'),
            TextField::new('titleEn', 'Titre (EN)')->setHelp('Si vide, le titre FR est affiché.'),
            IntegerField::new('position', 'Ordre')->setHelp('Ordre des sections dans l\'onglet, de la plus petite à la plus grande valeur.'),
        ];
    }
}
