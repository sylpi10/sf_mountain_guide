// Éditeur riche des articles (admin) : CKEditor 5, licence GPL, servi depuis public/vendor/ckeditor5
document.addEventListener('DOMContentLoaded', () => {
    const {
        ClassicEditor, Essentials, Paragraph, Heading, Bold, Italic, Underline, Strikethrough,
        Link, AutoLink, List, BlockQuote, HorizontalLine, Alignment, RemoveFormat, PasteFromOffice,
        Image, ImageCaption, ImageStyle, ImageToolbar, ImageUpload, ImageResize, ImageInsert,
        ImageTextAlternative, AutoImage, LinkImage, SimpleUploadAdapter, MediaEmbed,
        Table, TableToolbar, SourceEditing,
    } = window.CKEDITOR;

    document.querySelectorAll('textarea.js-rich-editor').forEach((textarea) => {
        ClassicEditor.create(textarea, {
            licenseKey: 'GPL',
            language: 'fr',
            translations: [window.CKEDITOR_TRANSLATIONS],
            plugins: [
                Essentials, Paragraph, Heading, Bold, Italic, Underline, Strikethrough,
                Link, AutoLink, List, BlockQuote, HorizontalLine, Alignment, RemoveFormat, PasteFromOffice,
                Image, ImageCaption, ImageStyle, ImageToolbar, ImageUpload, ImageResize, ImageInsert,
                ImageTextAlternative, AutoImage, LinkImage, SimpleUploadAdapter, MediaEmbed,
                Table, TableToolbar, SourceEditing,
            ],
            toolbar: {
                items: [
                    'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', 'removeFormat', '|',
                    'link', 'insertImage', 'mediaEmbed', 'blockQuote', 'insertTable', 'horizontalLine', '|',
                    'bulletedList', 'numberedList', 'alignment', '|',
                    'undo', 'redo', 'sourceEditing',
                ],
                shouldNotGroupWhenFull: true,
            },
            // le titre de l'article est déjà le h1 de la page : intertitres en h2 / h3
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
                    // upcastAlso : les <h1> des anciens articles (CKEditor 4) deviennent des titres h2
                    { model: 'heading2', view: 'h2', title: 'Titre', class: 'ck-heading_heading2', upcastAlso: ['h1'] },
                    { model: 'heading3', view: 'h3', title: 'Sous-titre', class: 'ck-heading_heading3' },
                ],
            },
            image: {
                insert: { integrations: ['upload', 'url'] },
                toolbar: [
                    'imageStyle:inline', 'imageStyle:alignLeft', 'imageStyle:alignRight', 'imageStyle:block', '|',
                    'toggleImageCaption', 'imageTextAlternative', 'linkImage',
                ],
                resizeUnit: '%',
            },
            link: {
                defaultProtocol: 'https://',
                decorators: {
                    openInNewTab: {
                        mode: 'manual',
                        label: 'Ouvrir dans un nouvel onglet',
                        attributes: { target: '_blank', rel: 'noopener' },
                    },
                },
            },
            table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] },
            mediaEmbed: { previewsInData: true },
            simpleUpload: {
                uploadUrl: textarea.dataset.uploadUrl,
                headers: { 'X-CSRF-Token': textarea.dataset.uploadToken },
            },
        }).catch((error) => console.error(error));
    });
});
