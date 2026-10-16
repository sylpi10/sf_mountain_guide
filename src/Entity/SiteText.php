<?php

namespace App\Entity;

use App\Repository\SiteTextRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Textes fixes du site modifiables en admin (FR / EN), une ligne par emplacement.
 * Les lignes sont créées par migration : l'admin ne fait que les modifier.
 */
#[ORM\Entity(repositoryClass: SiteTextRepository::class)]
class SiteText
{
    public const SLOTS = [
        'home_intro' => 'Accueil - texte de présentation des activités',
        'activities_intro' => 'Page Activités - texte d\'introduction',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private $slot;

    #[ORM\Column(type: 'text', nullable: true)]
    private $content;

    #[ORM\Column(type: 'text', nullable: true)]
    private $contentEn;

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
        return self::SLOTS[$this->slot] ?? (string) $this->slot;
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
