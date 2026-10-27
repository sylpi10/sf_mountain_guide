<?php

namespace App\Controller\Admin;

use App\Entity\PricingGroup;
use App\Form\PricingLineType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PricingGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PricingGroup::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Bloc de tarifs')
            ->setEntityLabelInPlural('Blocs de tarifs')
            ->setPageTitle('index', 'Tarifs : blocs')
            ->setHelp('index', 'Chaque bloc est une carte sur la page tarifs (Secteur Glacier Blanc, Tarifs cascade de glace…).')
            ->setDefaultSort(['section.position' => 'ASC', 'section.id' => 'ASC', 'position' => 'ASC', 'id' => 'ASC'])
            ->setPaginatorPageSize(50);
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets->addCssFile('css/admin-pricing.css');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add('section');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('section', 'Section'),
            TextField::new('title', 'Titre (FR)'),
            TextField::new('titleEn', 'Titre (EN)')->setHelp('Si vide, le titre FR est affiché.')->onlyOnForms(),
            IntegerField::new('position', 'Ordre')->setHelp('Ordre des blocs dans la section, de la plus petite à la plus grande valeur.'),
            CollectionField::new('lines', 'Lignes')
                ->setEntryType(PricingLineType::class)
                ->setHelp('Libellé + prix. Libellé seul : sous-titre dans le bloc. Prix seul : affiché en gros (ex. tarif à la journée).')
                ->allowAdd()
                ->allowDelete()
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms(),
            CollectionField::new('lines', 'Lignes')->onlyOnIndex(),
            TextareaField::new('note', 'Note (FR)')
                ->setHelp('Petit texte sous les lignes : ce qui est compris, conditions…')
                ->onlyOnForms(),
            TextareaField::new('noteEn', 'Note (EN)')->setHelp('Si vide, la note FR est affichée.')->onlyOnForms(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->renumberLines();
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->renumberLines();
        parent::updateEntity($entityManager, $entityInstance);
    }
}
