<?php

namespace App\Controller\Admin;

use App\Entity\AreaPage;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class AreaPageCrudController extends AbstractCrudController
{
    use RichTextEditorTrait;

    public function __construct(private CsrfTokenManagerInterface $csrfTokenManager)
    {
    }

    public static function getEntityFqcn(): string
    {
        return AreaPage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Page secteur')
            ->setEntityLabelInPlural('Pages secteurs')
            ->setPageTitle('index', 'Pages secteurs (référencement local)')
            ->setPageTitle('edit', fn (AreaPage $page) => 'Modifier : ' . $page->getName())
            ->setHelp('index', 'Une page par secteur, pour apparaître sur les recherches du type « guide Serre Chevalier ». Chaque texte doit rester unique (pas de copier-coller entre secteurs).')
            ->setHelp('new', 'La page n\'est visible sur le site (et dans le footer) qu\'une fois « Publiée » cochée.')
            ->setHelp('edit', 'La page n\'est visible sur le site (et dans le footer) qu\'une fois « Publiée » cochée.')
            ->setDefaultSort(['id' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $metaHelp = 'Texte affiché sous le lien dans Google : 150 à 160 caractères, avec le nom du secteur.';

        return [
            TextField::new('name', 'Secteur')->setHelp('Nom court, utilisé dans le footer.'),
            BooleanField::new('isPublished', 'Publiée'),
            TextField::new('slug', 'Adresse (FR)')
                ->setHelp('Ex. : guide-vallouise → directicimes.com/guide-vallouise. Minuscules, chiffres et tirets, avec le mot-clé recherché. À ne plus changer une fois la page publiée : l\'ancienne adresse indexée par Google serait perdue.'),
            TextField::new('slugEn', 'Adresse (EN)')
                ->setHelp('Ex. : mountain-guide-vallouise → directicimes.com/en/mountain-guide-vallouise. Mêmes règles, en anglais.')
                ->onlyOnForms(),
            TextField::new('title', 'Titre H1 (FR)')->setHelp('Ex. : Guide de ski à Serre Chevalier.'),
            TextField::new('titleEn', 'Titre H1 (EN)')->onlyOnForms(),
            TextField::new('seoTitle', 'Titre Google (FR)')
                ->setHelp('Titre de l\'onglet et du lien dans Google : 60 caractères max. Si vide : « Titre H1 - Directicimes ».')
                ->setMaxLength(70)
                ->onlyOnForms(),
            TextField::new('seoTitleEn', 'Titre Google (EN)')
                ->setHelp('Si vide : « Titre H1 (EN) - Directicimes ».')
                ->onlyOnForms(),
            TextareaField::new('metaDescription', 'Description Google (FR)')->setHelp($metaHelp)->onlyOnForms(),
            TextareaField::new('metaDescriptionEn', 'Description Google (EN)')->onlyOnForms(),
            $this->richTextField('content', 'Texte de la page (FR)')
                ->setHelp('Bouton « Insérer une image » pour ajouter des photos du secteur (réduites et converties automatiquement). Pensez au texte alternatif : décrire le lieu (ex. « Ski de randonnée au col de l\'Échelle »).')
                ->onlyOnForms(),
            $this->richTextField('contentEn', 'Texte de la page (EN)')
                ->setHelp('Si vide, le texte FR est affiché.')
                ->onlyOnForms(),
            DateTimeField::new('updatedAt', 'Modifiée le')->hideOnForm(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        parent::updateEntity($entityManager, $entityInstance);
    }
}
