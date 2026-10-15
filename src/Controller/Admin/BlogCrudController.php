<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class BlogCrudController extends AbstractCrudController
{
    public function __construct(private CsrfTokenManagerInterface $csrfTokenManager)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Blog::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre'),
            TextField::new('englishTitle', 'Titre anglais'),
            $this->richTextField('content', 'Mon texte'),
            $this->richTextField('englishContent', 'Mon texte en anglais'),
            TextField::new('location', 'Lieu'),
            ImageField::new('image', 'Image')
                ->onlyOnIndex()
                ->setBasePath('/uploads'),
            TextField::new('imageFile')
                ->onlyOnForms()
                ->setFormType(VichImageType::class),
            AssociationField::new('comments', 'Nb de coms')
                ->onlyOnIndex(),
        ];
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Articles')
            ->setPageTitle('edit', 'Modifier Un Article')
            ->setPageTitle('new', 'Créer Un Article');
    }

    // CKEditor 5 (public/vendor/ckeditor5, copié par `npm install`), initialisé par js/admin-editor.js
    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addCssFile('vendor/ckeditor5/ckeditor5.css')
            ->addCssFile('css/admin-editor.css')
            ->addJsFile('vendor/ckeditor5/ckeditor5.umd.js')
            ->addJsFile('vendor/ckeditor5/translations/fr.umd.js')
            ->addJsFile('js/admin-editor.js');
    }

    private function richTextField(string $property, string $label): TextareaField
    {
        return TextareaField::new($property, $label)
            ->hideOnIndex()
            ->renderAsHtml()
            // le textarea est masqué par l'éditeur : pas de « required » navigateur, NotBlank côté serveur
            ->setRequired(false)
            ->setFormTypeOptions([
                'empty_data' => '',
                'attr' => [
                    'class' => 'js-rich-editor',
                    'data-upload-url' => $this->generateUrl('admin_editor_upload'),
                    'data-upload-token' => $this->csrfTokenManager->getToken(EditorUploadController::CSRF_ID)->getValue(),
                ],
            ]);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions

            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            // ...
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Ajouter un Article');
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
