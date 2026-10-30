<?php

namespace App\Entity;

use App\Repository\LegalPageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pages légales éditables en admin (CGV…), une ligne par page, créée par migration.
 * Invisibles (404, pas de lien footer) tant qu'elles ne sont pas publiées.
 */
#[ORM\Entity(repositoryClass: LegalPageRepository::class)]
class LegalPage
{
    // slugs servis par la route /{slug} (et /en/{slug}) : ajouter ici, dans SLUGS_EN + une ligne en migration pour une nouvelle page
    public const SLUG_REQUIREMENT = 'cgv|mentions-legales|terms-and-conditions|legal-notice';

    // slug FR => slug de la version anglaise
    public const SLUGS_EN = [
        'cgv' => 'terms-and-conditions',
        'mentions-legales' => 'legal-notice',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private $slug;

    #[ORM\Column(type: 'string', length: 150)]
    private $title;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    private $titleEn;

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
        return (string) $this->title;
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
