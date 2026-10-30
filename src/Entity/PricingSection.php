<?php

namespace App\Entity;

use App\Enum\PricingSeason;
use App\Repository\PricingSectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SortDirection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Section de la page tarifs (« Massif du Mont Blanc », « Grandes voies »…) dans l'onglet hiver ou été, regroupant des blocs de tarifs.
 */
#[ORM\Entity(repositoryClass: PricingSectionRepository::class)]
class PricingSection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, enumType: PricingSeason::class)]
    #[Assert\NotNull]
    private ?PricingSeason $season = null;

    // facultatif : une section seule dans son onglet n'a pas besoin de titre
    #[ORM\Column(length: 150, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $titleEn = null;

    #[ORM\Column]
    private int $position = 0;

    /** @var Collection<int, PricingGroup> */
    #[ORM\OneToMany(targetEntity: PricingGroup::class, mappedBy: 'section')]
    #[ORM\OrderBy(['position' => SortDirection::Ascending, 'id' => SortDirection::Ascending])]
    private Collection $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->season?->label() . ($this->title ? ' – ' . $this->title : '');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): ?PricingSeason
    {
        return $this->season;
    }

    public function setSeason(?PricingSeason $season): self
    {
        $this->season = $season;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
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

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = (int) $position;
        return $this;
    }

    /** @return Collection<int, PricingGroup> */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(PricingGroup $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->setSection($this);
        }
        return $this;
    }
}
