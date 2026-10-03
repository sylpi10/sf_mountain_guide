<?php

namespace App\Controller\Admin;

use App\Entity\SiteText;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SiteTextCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SiteText::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Texte présentation des activités")
            ->setEntityLabelInPlural("Texte présentation des activités")
            ->setPageTitle("index", "Texte présentation des activités")
            ->setPageTitle(
                "edit",
                fn(SiteText $siteText) => $siteText->getLabel(),
            )
            ->setDefaultSort(["id" => "ASC"]);
    }

    // emplacements fixes, créés par migration
    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::NEW, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        $help = "Texte simple. Laisser une ligne vide entre deux paragraphes.";

        return [
            TextField::new("label", "Emplacement")->hideOnForm(),
            TextareaField::new("content", "Texte (FR)")
                ->setNumOfRows(8)
                ->setHelp($help)
                ->setMaxLength(120),
            TextareaField::new("contentEn", "Texte (EN)")
                ->setNumOfRows(8)
                ->setHelp($help . " Si vide, le texte FR est affiché.")
                ->setMaxLength(120),
            DateTimeField::new("updatedAt", "Modifié le")->hideOnForm(),
        ];
    }

    public function updateEntity(
        EntityManagerInterface $entityManager,
        $entityInstance,
    ): void {
        $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        parent::updateEntity($entityManager, $entityInstance);
    }
}
