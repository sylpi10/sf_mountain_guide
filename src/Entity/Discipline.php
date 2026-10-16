<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Attribute as Vich;
use App\Repository\DisciplineRepository;
use App\Enum\PriceUnit;
use Symfony\Component\Validator\Constraints as Assert;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: DisciplineRepository::class)]
class Discipline
{
    // largeurs WebP générées à l'upload, par mapping Vich (la plus grande = image principale)
    public const IMAGE_WIDTHS = [
        'discipline_images' => [480, 960],
        'discipline_images_detail' => [1280, 1920, 2560],
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'text')]
    private $persNumber;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $duration;

    #[ORM\Column(type: 'text', nullable: true)]
    private $location;

    #[ORM\Column(type: 'text')]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    private $englishTitle;

    // titre de l'onglet / du lien dans Google (sinon « Nom - Directicimes »)
    #[ORM\Column(type: 'string', length: 70, nullable: true)]
    private $seoTitle;

    #[ORM\Column(type: 'string', length: 70, nullable: true)]
    private $seoTitleEn;

    #[ORM\Column(type: 'text')]
    private $englishContent;

    #[ORM\Column(type: 'text')]
    private $englishNbPers;

    #[ORM\Column(type: 'string', length: 255)]
    private $englishDuration;

    #[ORM\Column(type: 'text', nullable: true)]
    private $englishLocation;

    #[ORM\Column(type: 'string', length: 255)]
    private $englishPrice;

    // infos courtes de la carte produit (accueil, autres activités) : chiffres seuls,
    // le texte autour est traduit dans le composant DisciplineCard
    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    private ?int $maxPeopleBeginner = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    private ?int $maxPeopleTechnicalMin = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    #[Assert\GreaterThanOrEqual(propertyPath: 'maxPeopleTechnicalMin')]
    private ?int $maxPeopleTechnicalMax = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    private ?int $priceFrom = null;

    #[ORM\Column(length: 20, nullable: true, enumType: PriceUnit::class)]
    private ?PriceUnit $priceUnit = null;

    // accroche courte de la page Activités (texte simple)
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $teaser = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $teaserEn = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(type: 'datetime', name: 'updated_at')]
    private $updatedAt;

    /**
     * @var string|null;
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[Vich\UploadableField(mapping: 'discipline_images', fileNameProperty: 'image')]
    private $imageFile;

    /**
     * @var string|null;
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageDetail;

    #[Vich\UploadableField(mapping: 'discipline_images_detail', fileNameProperty: 'imageDetail')]
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

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(?string $seoTitle): self
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    public function getSeoTitleEn(): ?string
    {
        return $this->seoTitleEn;
    }

    public function setSeoTitleEn(?string $seoTitleEn): self
    {
        $this->seoTitleEn = $seoTitleEn;

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
    public function setImageFile(?File $imageFile = null)
    {
        $this->imageFile = $imageFile;
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if (null !== $imageFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime("now");
        }
        return $this;
    }

    /**
     *
     * @param File $image_detailFile
     * @return void
     */
    public function setImageDetailFile(?File $imageDetailFile = null)
    {
        $this->imageDetailFile = $imageDetailFile;
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if (null !== $imageDetailFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime("now");
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
     * Get the value of updatedAt
     *
     * @return  \DateTimeInterface
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getMaxPeopleBeginner(): ?int
    {
        return $this->maxPeopleBeginner;
    }

    public function setMaxPeopleBeginner(?int $maxPeopleBeginner): self
    {
        $this->maxPeopleBeginner = $maxPeopleBeginner;

        return $this;
    }

    public function getMaxPeopleTechnicalMin(): ?int
    {
        return $this->maxPeopleTechnicalMin;
    }

    public function setMaxPeopleTechnicalMin(?int $maxPeopleTechnicalMin): self
    {
        $this->maxPeopleTechnicalMin = $maxPeopleTechnicalMin;

        return $this;
    }

    public function getMaxPeopleTechnicalMax(): ?int
    {
        return $this->maxPeopleTechnicalMax;
    }

    public function setMaxPeopleTechnicalMax(?int $maxPeopleTechnicalMax): self
    {
        $this->maxPeopleTechnicalMax = $maxPeopleTechnicalMax;

        return $this;
    }

    public function getPriceFrom(): ?int
    {
        return $this->priceFrom;
    }

    public function setPriceFrom(?int $priceFrom): self
    {
        $this->priceFrom = $priceFrom;

        return $this;
    }

    public function getPriceUnit(): ?PriceUnit
    {
        return $this->priceUnit;
    }

    public function setPriceUnit(?PriceUnit $priceUnit): self
    {
        $this->priceUnit = $priceUnit;

        return $this;
    }

    public function getTeaser(): ?string
    {
        return $this->teaser;
    }

    public function setTeaser(?string $teaser): self
    {
        $this->teaser = $teaser;

        return $this;
    }

    public function getTeaserEn(): ?string
    {
        return $this->teaserEn;
    }

    public function setTeaserEn(?string $teaserEn): self
    {
        $this->teaserEn = $teaserEn;

        return $this;
    }
}
