<?php

namespace App\Controller\Admin;

use App\Entity\PricingPage;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PricingPageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PricingPage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Page tarifs')
            ->setEntityLabelInPlural('Page tarifs')
            ->setPageTitle('index', 'Page tarifs')
            ->setPageTitle('edit', 'Modifier la page tarifs')
            ->setHelp('edit', 'Les tarifs eux-mêmes se saisissent dans Tarifs > Blocs de tarifs.');
    }

    // page unique, créée par migration
    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::NEW, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('season', 'Saison (FR)')->setHelp('Ex. : Saison hiver 2026 - 2027'),
            TextField::new('seasonEn', 'Saison (EN)')->onlyOnForms(),
            TextEditorField::new('content', 'Introduction (FR)')
                ->setHelp('Affichée en haut de la page, avant les tarifs : conditions générales, devis…')
                ->onlyOnForms(),
            TextEditorField::new('contentEn', 'Introduction (EN)')
                ->setHelp('Si vide, le texte FR est affiché.')
                ->onlyOnForms(),
            DateTimeField::new('updatedAt', 'Modifiée le')->hideOnForm(),
        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        parent::updateEntity($entityManager, $entityInstance);
    }
}
