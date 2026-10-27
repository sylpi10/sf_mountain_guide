<?php

namespace App\Entity;

use App\Repository\AreaPageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pages « secteur » pour le référencement local (guide à Serre Chevalier, Montgenèvre…),
 * une ligne par page créée par migration. Invisibles tant qu'elles ne sont pas publiées.
 */
#[ORM\Entity(repositoryClass: AreaPageRepository::class)]
class AreaPage
{
    // slugs servis par la route /{slug} (et /en/{slug}) : ajouter ici, dans SLUGS_EN + une ligne en migration pour un nouveau secteur
    public const SLUG_REQUIREMENT = 'guide-serre-chevalier|guide-montgenevre|guide-vallee-de-la-claree'
        .'|mountain-guide-serre-chevalier|mountain-guide-montgenevre|mountain-guide-claree-valley';

    // slug FR => slug de la version anglaise
    public const SLUGS_EN = [
        'guide-serre-chevalier' => 'mountain-guide-serre-chevalier',
        'guide-montgenevre' => 'mountain-guide-montgenevre',
        'guide-vallee-de-la-claree' => 'mountain-guide-claree-valley',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 80, unique: true)]
    private $slug;

    // nom court du secteur (liens du footer)
    #[ORM\Column(type: 'string', length: 80)]
    private $name;

    #[ORM\Column(type: 'string', length: 150)]
    private $title;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    private $titleEn;

    // titre de l'onglet / du lien Google, si le H1 est trop long (60 caractères max)
    #[ORM\Column(type: 'string', length: 70, nullable: true)]
    private $seoTitle;

    #[ORM\Column(type: 'string', length: 70, nullable: true)]
    private $seoTitleEn;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $metaDescription;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $metaDescriptionEn;

    #[ORM\Column(type: 'text', nullable: true)]
    private $content;

    #[ORM\Column(type: 'text', nullable: true)]
    private $contentEn;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private $isPublished = false;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // slug de l'URL dans la langue demandée (slug FR stocké en base, slug EN tiré de SLUGS_EN)
    public function getSlug(?string $locale = null): ?string
    {
        return 'en' === $locale ? (self::SLUGS_EN[$this->slug] ?? $this->slug) : $this->slug;
    }

    // slug FR (clé en base) correspondant à un slug d'URL, quelle que soit sa langue
    public static function frenchSlug(string $slug): string
    {
        return array_search($slug, self::SLUGS_EN, true) ?: $slug;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = (string) $name;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = (string) $title;
        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    public function setTitleEn(?string $titleEn): self
    {
        $this->titleEn = $titleEn;
        return $this;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(?string $seoTitle): self
    {
        $this->seoTitle = $seoTitle;
        return $this;
    }

    public function getSeoTitleEn(): ?string
    {
        return $this->seoTitleEn;
    }

    public function setSeoTitleEn(?string $seoTitleEn): self
    {
        $this->seoTitleEn = $seoTitleEn;
        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    public function getMetaDescriptionEn(): ?string
    {
        return $this->metaDescriptionEn;
    }

    public function setMetaDescriptionEn(?string $metaDescriptionEn): self
    {
        $this->metaDescriptionEn = $metaDescriptionEn;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getContentEn(): ?string
    {
        return $this->contentEn;
    }

    public function setContentEn(?string $contentEn): self
    {
        $this->contentEn = $contentEn;
        return $this;
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
