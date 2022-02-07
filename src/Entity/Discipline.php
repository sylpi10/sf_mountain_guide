<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DisciplineRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=DisciplineRepository::class)
 * @Vich\Uploadable
 */
class Discipline
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="text")
     */
    private $persNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(type="text")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $englishTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $englishContent;

    /**
     * @ORM\Column(type="text")
     */
    private $englishNbPers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $englishDuration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $englishLocation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $englishPrice;


    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null;
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="discipline_images", fileNameProperty="image")
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null;
     */
    private $imageDetail;

    /**
     * @Vich\UploadableField(mapping="discipline_images_detail", fileNameProperty="imageDetail")
     * @var File|null
     */
    private $imageDetailFile;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPersNumber(): ?string
    {
        return $this->persNumber;
    }

    public function setPersNumber(string $persNumber): self
    {
        $this->persNumber = $persNumber;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }


    public function getEnglishTitle(): ?string
    {
        return $this->englishTitle;
    }

    public function setEnglishTitle(string $englishTitle): self
    {
        $this->englishTitle = $englishTitle;

        return $this;
    }

    public function getEnglishContent(): ?string
    {
        return $this->englishContent;
    }

    public function setEnglishContent(string $englishContent): self
    {
        $this->englishContent = $englishContent;

        return $this;
    }

    public function getEnglishNbPers(): ?string
    {
        return $this->englishNbPers;
    }

    public function setEnglishNbPers(string $englishNbPers): self
    {
        $this->englishNbPers = $englishNbPers;

        return $this;
    }

    public function getEnglishDuration(): ?string
    {
        return $this->englishDuration;
    }

    public function setEnglishDuration(string $englishDuration): self
    {
        $this->englishDuration = $englishDuration;

        return $this;
    }

    public function getEnglishLocation(): ?string
    {
        return $this->englishLocation;
    }

    public function setEnglishLocation(?string $englishLocation): self
    {
        $this->englishLocation = $englishLocation;

        return $this;
    }

    public function getEnglishPrice(): ?string
    {
        return $this->englishPrice;
    }

    public function setEnglishPrice(string $englishPrice): self
    {
        $this->englishPrice = $englishPrice;

        return $this;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->title);
    }


    public function __toString()
    {
        return $this->title;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
    public function setImage($image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    /**
     *
     * @param File $imageFile
     * @return void
     */
    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if (null !== $imageFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    /**
     *
     * @param File $image_detailFile
     * @return void
     */
    public function setImageDetailFile(File $imageDetailFile = null)
    {
        $this->imageDetailFile = $imageDetailFile;
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if (null !== $imageDetailFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageDetailFile(): ?File
    {
        return $this->imageDetailFile;
    }

    /**
     * Get the value of image_detail
     *
     * @return  string|null;
     */
    public function getImageDetail()
    {
        return $this->imageDetail;
    }

    /**
     * Set the value of image_detail
     *
     * @param  string|null;  $image_detail
     *
     * @return  self
     */
    public function setImageDetail($imageDetail)
    {
        $this->imageDetail = $imageDetail;

        return $this;
    }

    /**
     * Get the value of updated_at
     *
     * @return  \DateTimeInterface
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
