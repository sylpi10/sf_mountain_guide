<?php

namespace App\Entity;

use App\Enum\PricingSeason;
use App\Repository\ActivityGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Groupe d'activités du menu « Activités » et de la page Activités (Hiver, Été, Autonomie…), géré en admin.
 * La saison (facultative) met le groupe en avant quand elle est en cours : en tête du menu et sur l'accueil.
 */
#[ORM\Entity(repositoryClass: ActivityGroupRepository::class)]
class ActivityGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $nameEn = null;

    #[ORM\Column(length: 20, nullable: true, enumType: PricingSeason::class)]
    private ?PricingSeason $season = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $position = 0;

    /**
     * @var Collection<int, Discipline>
     */
    #[ORM\OneToMany(targetEntity: Discipline::class, mappedBy: 'activityGroup')]
    #[ORM\OrderBy(['position' => 'ASC', 'id' => 'ASC'])]
    private Collection $disciplines;

    public function __construct()
    {
        $this->disciplines = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(?string $locale = null): ?string
    {
        return 'en' === $locale ? ($this->nameEn ?: $this->name) : $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = (string) $name;

        return $this;
    }

    public function getNameEn(): ?string
    {
        return $this->nameEn;
    }

    public function setNameEn(?string $nameEn): self
    {
        $this->nameEn = $nameEn;

        return $this;
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

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = (int) $position;

        return $this;
    }

    /**
     * @return Collection<int, Discipline>
     */
    public function getDisciplines(): Collection
    {
        return $this->disciplines;
    }

    /**
     * @return Discipline[] disciplines visibles sur le site
     */
    public function getPublishedDisciplines(): array
    {
        return array_values($this->disciplines->filter(fn (Discipline $d) => $d->isPublished())->toArray());
    }
}
