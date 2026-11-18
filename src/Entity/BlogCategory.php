<?php

namespace App\Entity;

use App\Repository\BlogCategoryRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

// catégorie d'article : filtres en pastilles au-dessus de la liste du blog (/blog?categorie=slug)
#[ORM\Entity(repositoryClass: BlogCategoryRepository::class)]
#[UniqueEntity('name', message: 'Cette catégorie existe déjà.')]
class BlogCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60, unique: true)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $englishName = null;

    // ordre d'affichage des pastilles (croissant)
    #[ORM\Column]
    private int $position = 0;

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEnglishName(): ?string
    {
        return $this->englishName;
    }

    public function setEnglishName(?string $englishName): static
    {
        $this->englishName = $englishName;

        return $this;
    }

    // nom affiché dans la langue demandée (repli sur le nom FR)
    public function getLabel(?string $locale = null): string
    {
        return (string) ('en' === $locale && $this->englishName ? $this->englishName : $this->name);
    }

    // slug de l'URL de filtre, identique en FR et EN (tiré du nom FR) : un lien partagé marche dans les deux langues
    public function getSlug(): string
    {
        return (new Slugify())->slugify((string) $this->name);
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }
}
