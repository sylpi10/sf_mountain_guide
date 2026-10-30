<?php

namespace App\Entity;

use App\Repository\SiteImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Images fixes du site (hero, contact, à propos), une ligne par emplacement.
 * Les lignes sont créées par migration : l'admin ne fait que les modifier.
 */
#[ORM\Entity(repositoryClass: SiteImageRepository::class)]
class SiteImage
{
    public const PUBLIC_DIR = '/uploads/site';

    /**
     * widths : largeurs générées à l'upload (jamais d'agrandissement)
     * ratio  : recadrage centré largeur/hauteur, optionnel.
     */
    public const SLOTS = [
        'hero' => ['label' => 'Hero - image principale (ordinateur)', 'widths' => [768, 1280, 1920]],
        'hero_mobile' => ['label' => 'Hero - version mobile (optionnelle, sinon image ordinateur)', 'widths' => [480, 768, 1080]],
        'contact' => ['label' => 'Photo du formulaire de contact', 'widths' => [216, 432], 'ratio' => 1],
        'about_1' => ['label' => 'À propos - image 1', 'widths' => [500, 1000]],
        'about_2' => ['label' => 'À propos - image 2', 'widths' => [500, 1000]],
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private $slot;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $alt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $altEn;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    public function __toString(): string
    {
        return $this->getLabel();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlot(): ?string
    {
        return $this->slot;
    }

    public function getLabel(): string
    {
        return self::SLOTS[$this->slot]['label'] ?? (string) $this->slot;
    }

    public function getWidths(): array
    {
        return self::SLOTS[$this->slot]['widths'] ?? [1280];
    }

    public function getRatio(): ?float
    {
        return self::SLOTS[$this->slot]['ratio'] ?? null;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getAltEn(): ?string
    {
        return $this->altEn;
    }

    public function setAltEn(?string $altEn): self
    {
        $this->altEn = $altEn;

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
