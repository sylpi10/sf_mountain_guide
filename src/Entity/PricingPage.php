<?php

namespace App\Entity;

use App\Repository\PricingPageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Page « Tarifs » (une seule ligne, créée par migration), mise à jour à chaque saison.
 * Les prix « à partir de » de chaque activité restent dans Discipline::price.
 */
#[ORM\Entity(repositoryClass: PricingPageRepository::class)]
class PricingPage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $season;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $seasonEn;

    #[ORM\Column(type: 'text', nullable: true)]
    private $content;

    #[ORM\Column(type: 'text', nullable: true)]
    private $contentEn;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    public function __toString(): string
    {
        return 'Page tarifs';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): ?string
    {
        return $this->season;
    }

    public function setSeason(?string $season): self
    {
        $this->season = $season;
        return $this;
    }

    public function getSeasonEn(): ?string
    {
        return $this->seasonEn;
    }

    public function setSeasonEn(?string $seasonEn): self
    {
        $this->seasonEn = $seasonEn;
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
