<?php

namespace App\Controller\Admin;

use App\Entity\Discipline;
use App\Enum\PriceUnit;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Vich\UploaderBundle\Form\Type\VichImageType;

class DisciplineCrudController extends AbstractCrudController
{
    // private $adminUrlGenerator;

    // public function __construct(EntityManagerInterface $entityManager, CrudUrlGenerator $crudUrlGenerator, AdminUrlGenerator $adminUrlGenerator)
    // {
    //     $this->entityManager = $entityManager;
    //     $this->crudUrlGenerator = $crudUrlGenerator;
    //     $this->adminUrlGenerator = $adminUrlGenerator;
    // }

    public static function getEntityFqcn(): string
    {
        return Discipline::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Disciplines - Activités')
            ->setPageTitle('edit', 'Modifier une discipline')
            ->setPageTitle('new', 'Ajouter Un Discipline');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Nom de discipline'),
            TextField::new('englishTitle', 'Nom de discipline en anglais'),
            TextField::new('seoTitle', 'Titre Google')
                ->setHelp('Titre de l\'onglet et du lien dans les résultats Google : activité + lieu, 60 caractères max. Si vide : « Nom - Directicimes ».')
                ->setMaxLength(70)
                ->onlyOnForms(),
            TextField::new('seoTitleEn', 'Titre Google en anglais')
                ->setHelp('Si vide, le titre Google FR est utilisé.')
                ->onlyOnForms(),
            TextEditorField::new('content', 'Description de l\'activité'),
            TextEditorField::new('englishContent', 'Description en anglais'),
            TextEditorField::new('persNumber', 'Nombre de participants')->onlyOnForms(),
            TextEditorField::new('englishNbPers', 'Nombre de participants en anglais')->onlyOnForms(),
            TextField::new('duration', 'Durée')->onlyOnForms(),
            TextField::new('englishDuration', 'Durée en anglais')->onlyOnForms(),
            TextField::new('location', 'Lieu de l\'activité')->onlyOnForms(),
            TextField::new('englishLocation', 'Lieu de l\'activité en anglais')->onlyOnForms(),
            TextField::new('price', 'Prix')->onlyOnForms(),
            TextField::new('englishPrice', 'Prix en anglais')->onlyOnForms(),
            ImageField::new('image', 'Image pour la liste')
                ->onlyOnIndex()
                ->setBasePath('/uploads'),
            TextField::new('imageFile', 'Image pour la liste')
                ->onlyOnForms()
                ->setFormType(VichImageType::class),
            ImageField::new('image', 'Image de fond')
                ->onlyOnIndex()
                ->setBasePath('/uploads'),
            TextField::new('imageDetailFile', 'image de fond de la page de détails')
                ->onlyOnForms()
                ->setFormType(VichImageType::class),

            FormField::addFieldset('Carte produit (accueil et « autres activités »)')
                ->setHelp('Chiffres seuls, le texte et la traduction anglaise sont ajoutés automatiquement. Laisser vide pour ne pas afficher la ligne.'),
            IntegerField::new('maxPeopleBeginner', 'Pers. max en initiation')
                ->setHelp('Si aucun itinéraire technique n\'est renseigné, affiché simplement « X pers. max ».')
                ->onlyOnForms(),
            IntegerField::new('maxPeopleTechnicalMin', 'Pers. min sur itinéraire technique')
                ->setHelp('Optionnel : permet d\'afficher « 1 à 2 pers. ».')
                ->onlyOnForms(),
            IntegerField::new('maxPeopleTechnicalMax', 'Pers. max sur itinéraire technique')
                ->setHelp('Sommet difficile, grandes voies, cascade en plusieurs longueurs…')
                ->onlyOnForms(),
            IntegerField::new('priceFrom', 'Prix à partir de (€)')
                ->onlyOnForms(),
            ChoiceField::new('priceUnit', 'Prix par')
                ->setFormTypeOption('choice_label', fn (PriceUnit $unit) => $unit->label())
                ->onlyOnForms(),

            FormField::addFieldset('Page Activités'),
            TextareaField::new('teaser', 'Accroche')
                ->setNumOfRows(3)
                ->setHelp('1 ou 2 phrases, texte simple : donne envie de cliquer sur l\'activité.')
                ->onlyOnForms(),
            TextareaField::new('teaserEn', 'Accroche en anglais')
                ->setNumOfRows(3)
                ->setHelp('Si vide, l\'accroche FR est affichée.')
                ->onlyOnForms(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions

            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            // ...
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Ajouter une Discipline');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-pencil-alt')->setLabel('Modifier');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, function (Action $action) {
                return $action->setIcon('fa fa-pencil-alt')->setLabel('Sauver et continuer l\'édition');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setIcon('fa fa-check')->setLabel('Enregister');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fa fa-eye')->setLabel('Détails');
            })

            ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')->setLabel('Supprimer');
            })
            ->update(Crud::PAGE_DETAIL, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-pencil')->setLabel('Modifier');
            })

            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-times')->setLabel('Supprimer');
            })

            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Sauver et Créer un autre');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setIcon('fa fa-check')->setLabel('Valider');
            })
            // in PHP 7.4 and newer you can use arrow functions
            // ->update(Crud::PAGE_INDEX, Action::NEW,
            //     fn (Action $action) => $action->setIcon('fa fa-file-alt')->setLabel(false))
        ;
    }
}
