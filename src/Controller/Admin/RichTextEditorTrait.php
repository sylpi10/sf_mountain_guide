<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

/**
 * Éditeur riche CKEditor 5 (titres, liens, photos uploadées…) pour les CRUD qui en ont besoin.
 * La classe qui l'utilise doit avoir une propriété $csrfTokenManager (CsrfTokenManagerInterface).
 */
trait RichTextEditorTrait
{
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
}
