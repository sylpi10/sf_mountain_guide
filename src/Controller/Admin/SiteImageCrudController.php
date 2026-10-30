<?php

namespace App\Controller\Admin;

use App\Entity\SiteImage;
use App\Service\ImageOptimizer;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image;

class SiteImageCrudController extends AbstractCrudController
{
    public function __construct(private ImageOptimizer $optimizer)
    {
    }

    public static function getEntityFqcn(): string
    {
        return SiteImage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Image du site')
            ->setEntityLabelInPlural('Images du site')
            ->setPageTitle('index', 'Images du site (hero, contact, à propos)')
            ->setPageTitle('edit', fn (SiteImage $siteImage) => $siteImage->getLabel())
            ->setDefaultSort(['id' => 'ASC']);
    }

    // emplacements fixes, créés par migration
    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::NEW, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        $siteImage = $this->getContext()?->getEntity()->getInstance();
        $widths = $siteImage instanceof SiteImage ? $siteImage->getWidths() : [1280];
        $ratio = $siteImage instanceof SiteImage ? $siteImage->getRatio() : null;

        return [
            TextField::new('label', 'Emplacement')->hideOnForm(),
            ImageField::new('image', 'Image')
                ->setBasePath(SiteImage::PUBLIC_DIR)
                ->setUploadDir('public'.SiteImage::PUBLIC_DIR)
                ->setUploadedFileNamePattern('[slug]-[timestamp].webp')
                ->setFileConstraints(new Image(maxSize: '20M', mimeTypes: ['image/jpeg', 'image/png', 'image/webp']))
                ->setRequired(false)
                ->setHelp(sprintf(
                    'JPG, PNG ou WebP. Converti automatiquement en WebP et redimensionné (%s px de large%s).',
                    implode(' / ', $widths),
                    1.0 === $ratio ? ', recadré en carré' : ''
                ))
                ->setFormTypeOptions([
                    'upload_new' => fn (UploadedFile $file, string $uploadDir, string $fileName) => $this->optimizer->process($file->getPathname(), $uploadDir.$fileName, $widths, $ratio),
                    // l'ancienne image est supprimée dans updateEntity(), une fois l'enregistrement réussi
                    'upload_delete' => static function () {
                    },
                ]),
            TextField::new('alt', 'Texte alternatif (FR)')->setHelp('Description de l\'image, pour le SEO et l\'accessibilité.'),
            TextField::new('altEn', 'Texte alternatif (EN)'),
            DateTimeField::new('updatedAt', 'Modifiée le')->hideOnForm(),
        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $previousImage = $entityManager->getUnitOfWork()->getOriginalEntityData($entityInstance)['image'] ?? null;

        $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        parent::updateEntity($entityManager, $entityInstance);

        if ($previousImage && $previousImage !== $entityInstance->getImage()) {
            $this->optimizer->delete($this->getParameter('kernel.project_dir').'/public'.SiteImage::PUBLIC_DIR.'/'.$previousImage);
        }
    }
}
