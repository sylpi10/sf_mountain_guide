<?php

namespace App\Controller\Admin;

use App\Entity\PricingPage;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PricingPageCrudController extends AbstractCrudController
{
    private const MONTHS = [
        'janvier' => 1, 'février' => 2, 'mars' => 3, 'avril' => 4, 'mai' => 5, 'juin' => 6,
        'juillet' => 7, 'août' => 8, 'septembre' => 9, 'octobre' => 10, 'novembre' => 11, 'décembre' => 12,
    ];

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
            ChoiceField::new('winterFromMonth', 'Onglet hiver ouvert à partir de')
                ->setChoices(self::MONTHS)
                ->setHelp('Onglet affiché par défaut en arrivant sur la page.')
                ->onlyOnForms(),
            ChoiceField::new('summerFromMonth', 'Onglet été ouvert à partir de')
                ->setChoices(self::MONTHS)
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
