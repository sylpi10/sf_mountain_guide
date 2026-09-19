<?php

namespace App\Entity;

use App\Repository\AboutRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AboutRepository::class)]
class About
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $whoTitle;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    private string $whoText;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $whoEnglishTitle;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    private string $whoEnglishText;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $whyTitle;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    private string $whyText;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $whyEnglishTitle;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    private string $whyEnglishText;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWhoTitle(): ?string
    {
        return $this->whoTitle;
    }

    public function setWhoTitle(string $whoTitle): self
    {
        $this->whoTitle = $whoTitle;

        return $this;
    }

    public function getWhoText(): ?string
    {
        return $this->whoText;
    }

    public function setWhoText(string $whoText): self
    {
        $this->whoText = $whoText;

        return $this;
    }

    public function getWhoEnglishTitle(): ?string
    {
        return $this->whoEnglishTitle;
    }

    public function setWhoEnglishTitle(string $whoEnglishTitle): self
    {
        $this->whoEnglishTitle = $whoEnglishTitle;

        return $this;
    }

    public function getWhoEnglishText(): ?string
    {
        return $this->whoEnglishText;
    }

    public function setWhoEnglishText(string $whoEnglishText): self
    {
        $this->whoEnglishText = $whoEnglishText;

        return $this;
    }

    public function getWhyTitle(): ?string
    {
        return $this->whyTitle;
    }

    public function setWhyTitle(string $whyTitle): self
    {
        $this->whyTitle = $whyTitle;

        return $this;
    }

    public function getWhyText(): ?string
    {
        return $this->whyText;
    }

    public function setWhyText(string $whyText): self
    {
        $this->whyText = $whyText;

        return $this;
    }

    public function getWhyEnglishTitle(): ?string
    {
        return $this->whyEnglishTitle;
    }

    public function setWhyEnglishTitle(string $whyEnglishTitle): self
    {
        $this->whyEnglishTitle = $whyEnglishTitle;

        return $this;
    }

    public function getWhyEnglishText(): ?string
    {
        return $this->whyEnglishText;
    }

    public function setWhyEnglishText(string $whyEnglishText): self
    {
        $this->whyEnglishText = $whyEnglishText;

        return $this;
    }
}
