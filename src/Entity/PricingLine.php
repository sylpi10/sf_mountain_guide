<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ligne d'un bloc de tarifs : libellé + prix (« Voie normale » / « 1000€ »).
 * Libellé seul : sous-titre dans le bloc ; prix seul : affiché en gros.
 */
#[ORM\Entity]
class PricingLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lines')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?PricingGroup $pricingGroup = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $labelEn = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $priceEn = null;

    #[ORM\Column]
    private int $position = 0;

    public function __toString(): string
    {
        return trim($this->label . ' ' . $this->price);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPricingGroup(): ?PricingGroup
    {
        return $this->pricingGroup;
    }

    public function setPricingGroup(?PricingGroup $pricingGroup): self
    {
        $this->pricingGroup = $pricingGroup;
        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function getLabelEn(): ?string
    {
        return $this->labelEn;
    }

    public function setLabelEn(?string $labelEn): self
    {
        $this->labelEn = $labelEn;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getPriceEn(): ?string
    {
        return $this->priceEn;
    }

    public function setPriceEn(?string $priceEn): self
    {
        $this->priceEn = $priceEn;
        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;
        return $this;
    }
}
