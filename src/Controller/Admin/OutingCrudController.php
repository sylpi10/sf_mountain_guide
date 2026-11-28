<?php

namespace App\Controller\Admin;

use App\Entity\Outing;
use App\Enum\OutingLevel;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class OutingCrudController extends AbstractCrudController
{
    use RichTextEditorTrait;

    public function __construct(private CsrfTokenManagerInterface $csrfTokenManager)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Outing::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $help = 'La sortie n\'est visible sur le site qu\'une fois « Publiée » cochée (et sa discipline publiée). Connecté en admin, le bouton « Voir sur le site » permet de la prévisualiser avant. Champs EN vides = texte FR affiché.';

        return $crud
            ->setEntityLabelInSingular('Sortie')
            ->setEntityLabelInPlural('Sorties')
            ->setPageTitle('index', 'Sorties')
            ->setPageTitle('edit', fn (Outing $outing) => 'Modifier : '.$outing->getTitle())
            ->setHelp('index', 'Les sorties proposées dans chaque discipline (ex. « Découverte du ski de rando » dans Ski de rando), avec leur fiche détaillée.')
            ->setHelp('new', $help)
            ->setHelp('edit', $help)
            ->setDefaultSort(['discipline' => 'ASC', 'position' => 'ASC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(EntityFilter::new('discipline', 'Discipline'));
    }

    public function configureActions(Actions $actions): Actions
    {
        $view = Action::new('view', 'Voir sur le site', 'fa fa-eye')
            ->linkToUrl(fn (Outing $outing) => $this->generateUrl('outing', [
                'disciplineSlug' => $outing->getDiscipline()->getSlug('fr'),
                'slug' => $outing->getSlug('fr'),
                'id' => $outing->getId(),
            ]))
            ->setHtmlAttributes(['target' => '_blank']);

        return $actions
            ->add(Crud::PAGE_INDEX, $view)
            ->add(Crud::PAGE_EDIT, $view);
    }

    public function configureFields(string $pageName): iterable
    {
        $listHelp = 'Un élément par ligne.';

        return [
            FormField::addTab('Présentation'),
            AssociationField::new('discipline', 'Discipline')
                ->setSortProperty('title'),
            TextField::new('title', 'Titre (FR)')->setHelp('Ex. : Découverte du ski de randonnée, Traversée des Écrins à ski.'),
            TextField::new('titleEn', 'Titre (EN)')->onlyOnForms(),
            BooleanField::new('isPublished', 'Publiée'),
            BooleanField::new('featuredOnHome', 'Sur l\'accueil')
                ->setHelp('Affichée dans « Les sorties du moment » de l\'accueil quand la saison de sa discipline est en cours (3 max).'),
            IntegerField::new('position', 'Ordre')->setHelp('Ordre dans la discipline : plus petit = en premier.'),
            ImageField::new('image', 'Photo')->onlyOnIndex()->setBasePath('/uploads'),
            TextField::new('imageFile', 'Photo')
                ->setFormType(VichImageType::class)
                ->setHelp('Grande photo paysage (1600 px de large ou plus) : convertie et réduite automatiquement.')
                ->onlyOnForms(),
            TextField::new('imageAlt', 'Description de la photo')
                ->setHelp('Pour Google et les malvoyants : ce que montre la photo (ex. « Montée en ski de rando au col du Galibier »). Si vide : le titre.')
                ->onlyOnForms(),
            TextareaField::new('teaser', 'Accroche (FR)')
                ->setNumOfRows(3)
                ->setHelp('1 ou 2 phrases, texte simple : affichée sur la carte, en haut de la fiche et dans Google.')
                ->onlyOnForms(),
            TextareaField::new('teaserEn', 'Accroche (EN)')->setNumOfRows(3)->onlyOnForms(),
            $this->richTextField('content', 'Description détaillée (FR)')
                ->setHelp('Le déroulé de la sortie, ce qui la rend unique. Bouton « Insérer une image » pour ajouter des photos.')
                ->onlyOnForms(),
            $this->richTextField('contentEn', 'Description détaillée (EN)')->onlyOnForms(),

            FormField::addTab('Infos pratiques'),
            ChoiceField::new('level', 'Niveau')
                ->setFormTypeOption('choice_label', fn (OutingLevel $level) => $level->label())
                ->formatValue(fn ($value, Outing $outing) => $outing->getLevel()?->label() ?? '—')
                ->setRequired(false),
            TextareaField::new('prerequisites', 'Niveau requis (FR)')
                ->setNumOfRows(2)
                ->setHelp('Ex. : à l\'aise sur piste rouge, bonne condition physique.')
                ->onlyOnForms(),
            TextareaField::new('prerequisitesEn', 'Niveau requis (EN)')->setNumOfRows(2)->onlyOnForms(),
            TextField::new('duration', 'Durée (FR)')->setHelp('Ex. : Journée, 2 jours, 6 jours.'),
            TextField::new('durationEn', 'Durée (EN)')->onlyOnForms(),
            IntegerField::new('elevation', 'Dénivelé (m)')->setHelp('Dénivelé positif par jour, chiffre seul.')->onlyOnForms(),
            IntegerField::new('maxPeople', 'Pers. max'),
            TextField::new('audience', 'Public (FR)')->setHelp('Ex. : Adultes et ados dès 14 ans.')->onlyOnForms(),
            TextField::new('audienceEn', 'Public (EN)')->onlyOnForms(),
            TextField::new('period', 'Période (FR)')->setHelp('Ex. : mi-décembre à mi-avril.')->onlyOnForms(),
            TextField::new('periodEn', 'Période (EN)')->onlyOnForms(),
            TextField::new('location', 'Secteur (FR)')->setHelp('Ex. : Serre Chevalier, vallée de la Clarée.')->onlyOnForms(),
            TextField::new('locationEn', 'Secteur (EN)')->onlyOnForms(),
            TextField::new('meetingPoint', 'Rendez-vous (FR)')->onlyOnForms(),
            TextField::new('meetingPointEn', 'Rendez-vous (EN)')->onlyOnForms(),

            FormField::addTab('Prix et matériel'),
            IntegerField::new('price', 'Prix à partir de (€)')->setHelp('Chiffre seul. Si vide : « Sur devis ».'),
            TextField::new('priceNote', 'Précision du prix (FR)')
                ->setHelp('Ex. : par personne, dès 3 participants / pour le groupe (1 à 4 pers.).')
                ->onlyOnForms(),
            TextField::new('priceNoteEn', 'Précision du prix (EN)')->onlyOnForms(),
            TextareaField::new('included', 'Le prix comprend (FR)')->setHelp($listHelp)->onlyOnForms(),
            TextareaField::new('includedEn', 'Le prix comprend (EN)')->onlyOnForms(),
            TextareaField::new('notIncluded', 'Le prix ne comprend pas (FR)')->setHelp($listHelp)->onlyOnForms(),
            TextareaField::new('notIncludedEn', 'Le prix ne comprend pas (EN)')->onlyOnForms(),
            TextareaField::new('equipmentToBring', 'Matériel à prévoir (FR)')->setHelp($listHelp)->onlyOnForms(),
            TextareaField::new('equipmentToBringEn', 'Matériel à prévoir (EN)')->onlyOnForms(),
            TextareaField::new('equipmentProvided', 'Matériel fourni (FR)')->setHelp($listHelp.' Ex. : DVA, pelle, sonde.')->onlyOnForms(),
            TextareaField::new('equipmentProvidedEn', 'Matériel fourni (EN)')->onlyOnForms(),

            FormField::addTab('Google'),
            TextField::new('seoTitle', 'Titre Google (FR)')
                ->setHelp('Titre de l\'onglet et du lien dans Google : sortie + lieu, 60 caractères max. Si vide : « Titre - Directicimes ».')
                ->setMaxLength(70)
                ->onlyOnForms(),
            TextField::new('seoTitleEn', 'Titre Google (EN)')->onlyOnForms(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdatedAt(new \DateTime());
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdatedAt(new \DateTime());
        parent::updateEntity($entityManager, $entityInstance);
    }
}
