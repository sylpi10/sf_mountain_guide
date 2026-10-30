<?php

namespace App\Entity;

use App\Repository\AreaPageRepository;
use App\Validator\AreaPageSlugs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pages « secteur » pour le référencement local (guide à Serre Chevalier, Montgenèvre…),
 * créées en admin. Invisibles tant qu'elles ne sont pas publiées.
 */
#[ORM\Entity(repositoryClass: AreaPageRepository::class)]
#[AreaPageSlugs]
class AreaPage
{
    // format des slugs servis par la route /{slug} (et /en/{slug}) : minuscules, chiffres, tirets
    public const SLUG_REQUIREMENT = '[a-z0-9]+(?:-[a-z0-9]+)*';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 80, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 80)]
    #[Assert\Regex('/^'.self::SLUG_REQUIREMENT.'$/', message: 'Minuscules, chiffres et tirets uniquement (ex. : guide-vallouise).')]
    private $slug;

    // slug de la version anglaise (/en/{slugEn})
    #[ORM\Column(type: 'string', length: 80, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 80)]
    #[Assert\Regex('/^'.self::SLUG_REQUIREMENT.'$/', message: 'Minuscules, chiffres et tirets uniquement (ex. : mountain-guide-vallouise).')]
    private $slugEn;

    // nom court du secteur (liens du footer)
    #[ORM\Column(type: 'string', length: 80)]
    #[Assert\NotBlank]
    private $name;

    #[ORM\Column(type: 'string', length: 150)]
    #[Assert\NotBlank]
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

    // slug de l'URL dans la langue demandée
    public function getSlug(?string $locale = null): ?string
    {
        return 'en' === $locale ? ($this->slugEn ?: $this->slug) : $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = (string) $slug;

        return $this;
    }

    public function getSlugEn(): ?string
    {
        return $this->slugEn;
    }

    public function setSlugEn(?string $slugEn): self
    {
        $this->slugEn = (string) $slugEn;

        return $this;
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
