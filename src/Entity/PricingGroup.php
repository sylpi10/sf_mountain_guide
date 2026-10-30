<?php

namespace App\Entity;

use App\Repository\PricingGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Bloc de tarifs (une carte sur la page) : « Secteur Glacier Blanc », « Tarifs cascade de glace »….
 */
#[ORM\Entity(repositoryClass: PricingGroupRepository::class)]
class PricingGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'groups')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private ?PricingSection $section = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $titleEn = null;

    // texte en petit sous les lignes (ce qui est compris, conditions…)
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $noteEn = null;

    #[ORM\Column]
    private int $position = 0;

    /** @var Collection<int, PricingLine> */
    #[ORM\OneToMany(targetEntity: PricingLine::class, mappedBy: 'pricingGroup', cascade: ['persist'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => \SortDirection::Ascending, 'id' => \SortDirection::Ascending])]
    #[Assert\Valid]
    private Collection $lines;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) ($this->title ?? $this->section);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSection(): ?PricingSection
    {
        return $this->section;
    }

    public function setSection(?PricingSection $section): self
    {
        $this->section = $section;

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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getNoteEn(): ?string
    {
        return $this->noteEn;
    }

    public function setNoteEn(?string $noteEn): self
    {
        $this->noteEn = $noteEn;

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

    /** @return Collection<int, PricingLine> */
    public function getLines(): Collection
    {
        return $this->lines;
    }

    public function addLine(PricingLine $line): self
    {
        if (!$this->lines->contains($line)) {
            $this->lines->add($line);
            $line->setPricingGroup($this);
        }

        return $this;
    }

    public function removeLine(PricingLine $line): self
    {
        $this->lines->removeElement($line);

        return $this;
    }

    /**
     * Les lignes gardent l'ordre du formulaire d'admin.
     */
    public function renumberLines(): void
    {
        $position = 0;
        foreach ($this->lines as $line) {
            $line->setPosition($position++);
        }
    }
}
