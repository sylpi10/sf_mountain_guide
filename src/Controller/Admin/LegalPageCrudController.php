<?php

namespace App\Controller\Admin;

use App\Entity\LegalPage;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LegalPageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LegalPage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Page légale')
            ->setEntityLabelInPlural('Pages légales')
            ->setPageTitle('index', 'Pages légales (CGV…)')
            ->setPageTitle('edit', fn (LegalPage $page) => 'Modifier : ' . $page->getTitle())
            ->setHelp('edit', 'La page n\'est visible sur le site (et dans le footer) qu\'une fois « Publiée » cochée.')
            ->setDefaultSort(['id' => 'ASC']);
    }

    // pages fixes, créées par migration
    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::NEW, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre (FR)'),
            TextField::new('titleEn', 'Titre (EN)')->onlyOnForms(),
            BooleanField::new('isPublished', 'Publiée'),
            TextEditorField::new('content', 'Contenu (FR)')->onlyOnForms(),
            TextEditorField::new('contentEn', 'Contenu (EN)')
                ->setHelp('Si vide, le contenu FR est affiché.')
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
