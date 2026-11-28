<?php

namespace App\Entity;

use App\Enum\OutingLevel;
use App\Repository\OutingRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

/**
 * Sortie proposée dans une discipline (« Découverte du ski de rando », « Traversée des Écrins »…) :
 * carte sur la page de la discipline et fiche détaillée avec infos pratiques et prix.
 * Les champs « En » vides retombent sur le français.
 */
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: OutingRepository::class)]
class Outing
{
    // largeurs WebP générées à l'upload (la plus grande = image principale)
    public const IMAGE_WIDTHS = [
        'outing_images' => [480, 960, 1600],
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'outings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Discipline $discipline = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 150)]
    private ?string $title = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $titleEn = null;

    // titre de l'onglet / du lien Google (sinon « Titre - Directicimes »)
    #[ORM\Column(length: 70, nullable: true)]
    private ?string $seoTitle = null;

    #[ORM\Column(length: 70, nullable: true)]
    private ?string $seoTitleEn = null;

    // chapô : 1 ou 2 phrases (carte et haut de la fiche), aussi utilisé comme meta description
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $teaser = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $teaserEn = null;

    // description détaillée (éditeur riche)
    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $content = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $contentEn = null;

    // niveau requis en clair (« savoir skier en piste rouge »…)
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $prerequisites = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $prerequisitesEn = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $duration = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $durationEn = null;

    // dénivelé positif en mètres
    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $elevation = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    private ?int $maxPeople = null;

    // public : « Adultes et ados dès 14 ans »…
    #[ORM\Column(length: 150, nullable: true)]
    private ?string $audience = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $audienceEn = null;

    // période propice : « mi-décembre à mi-avril »
    #[ORM\Column(length: 150, nullable: true)]
    private ?string $period = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $periodEn = null;

    // secteur / lieu de la sortie
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locationEn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $meetingPoint = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $meetingPointEn = null;

    // listes : un élément par ligne
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $included = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $includedEn = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notIncluded = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notIncludedEn = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $equipmentToBring = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $equipmentToBringEn = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $equipmentProvided = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $equipmentProvidedEn = null;

    // prix « à partir de » en euros
    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $price = null;

    // précision du prix : « par personne, dès 3 participants », « pour le groupe »…
    #[ORM\Column(length: 150, nullable: true)]
    private ?string $priceNote = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $priceNoteEn = null;

    #[ORM\Column(length: 20, nullable: true, enumType: OutingLevel::class)]
    private ?OutingLevel $level = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $position = 0;

    #[ORM\Column(options: ['default' => false])]
    private bool $isPublished = false;

    // mise en avant sur l'accueil quand la saison de sa discipline est en cours
    #[ORM\Column(options: ['default' => false])]
    private bool $featuredOnHome = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[Vich\UploadableField(mapping: 'outing_images', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    // texte alternatif de la photo (sinon le titre)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageAlt = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    public function __toString(): string
    {
        return (string) $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // slug de l'URL dans la langue demandée (titre anglais sous /en, repli sur le titre FR)
    public function getSlug(?string $locale = null): string
    {
        return (new Slugify())->slugify($this->getTitleFor($locale) ?? '');
    }

    public function getTitleFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->titleEn ? $this->titleEn : $this->title;
    }

    public function getSeoTitleFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->seoTitleEn ? $this->seoTitleEn : $this->seoTitle;
    }

    public function getTeaserFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->teaserEn ? $this->teaserEn : $this->teaser;
    }

    public function getContentFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->contentEn ? $this->contentEn : $this->content;
    }

    public function getPrerequisitesFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->prerequisitesEn ? $this->prerequisitesEn : $this->prerequisites;
    }

    public function getDurationFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->durationEn ? $this->durationEn : $this->duration;
    }

    public function getAudienceFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->audienceEn ? $this->audienceEn : $this->audience;
    }

    public function getPeriodFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->periodEn ? $this->periodEn : $this->period;
    }

    public function getLocationFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->locationEn ? $this->locationEn : $this->location;
    }

    public function getMeetingPointFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->meetingPointEn ? $this->meetingPointEn : $this->meetingPoint;
    }

    public function getIncludedFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->includedEn ? $this->includedEn : $this->included;
    }

    public function getNotIncludedFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->notIncludedEn ? $this->notIncludedEn : $this->notIncluded;
    }

    public function getEquipmentToBringFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->equipmentToBringEn ? $this->equipmentToBringEn : $this->equipmentToBring;
    }

    public function getEquipmentProvidedFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->equipmentProvidedEn ? $this->equipmentProvidedEn : $this->equipmentProvided;
    }

    public function getPriceNoteFor(?string $locale): ?string
    {
        return 'en' === $locale && $this->priceNoteEn ? $this->priceNoteEn : $this->priceNote;
    }

    /**
     * Liste saisie un élément par ligne (inclus, matériel…) dans la langue demandée.
     *
     * @return string[]
     */
    public function getListFor(string $field, ?string $locale): array
    {
        $text = $this->{'get'.ucfirst($field).'For'}($locale);

        return array_values(array_filter(array_map(fn ($line) => trim(ltrim(trim($line), '-•*')), preg_split('/\R/', (string) $text))));
    }

    public function getDiscipline(): ?Discipline
    {
        return $this->discipline;
    }

    public function setDiscipline(?Discipline $discipline): self
    {
        $this->discipline = $discipline;

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

    public function getPrerequisites(): ?string
    {
        return $this->prerequisites;
    }

    public function setPrerequisites(?string $prerequisites): self
    {
        $this->prerequisites = $prerequisites;

        return $this;
    }

    public function getPrerequisitesEn(): ?string
    {
        return $this->prerequisitesEn;
    }

    public function setPrerequisitesEn(?string $prerequisitesEn): self
    {
        $this->prerequisitesEn = $prerequisitesEn;

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

    public function getDurationEn(): ?string
    {
        return $this->durationEn;
    }

    public function setDurationEn(?string $durationEn): self
    {
        $this->durationEn = $durationEn;

        return $this;
    }

    public function getElevation(): ?int
    {
        return $this->elevation;
    }

    public function setElevation(?int $elevation): self
    {
        $this->elevation = $elevation;

        return $this;
    }

    public function getMaxPeople(): ?int
    {
        return $this->maxPeople;
    }

    public function setMaxPeople(?int $maxPeople): self
    {
        $this->maxPeople = $maxPeople;

        return $this;
    }

    public function getAudience(): ?string
    {
        return $this->audience;
    }

    public function setAudience(?string $audience): self
    {
        $this->audience = $audience;

        return $this;
    }

    public function getAudienceEn(): ?string
    {
        return $this->audienceEn;
    }

    public function setAudienceEn(?string $audienceEn): self
    {
        $this->audienceEn = $audienceEn;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(?string $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getPeriodEn(): ?string
    {
        return $this->periodEn;
    }

    public function setPeriodEn(?string $periodEn): self
    {
        $this->periodEn = $periodEn;

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

    public function getLocationEn(): ?string
    {
        return $this->locationEn;
    }

    public function setLocationEn(?string $locationEn): self
    {
        $this->locationEn = $locationEn;

        return $this;
    }

    public function getMeetingPoint(): ?string
    {
        return $this->meetingPoint;
    }

    public function setMeetingPoint(?string $meetingPoint): self
    {
        $this->meetingPoint = $meetingPoint;

        return $this;
    }

    public function getMeetingPointEn(): ?string
    {
        return $this->meetingPointEn;
    }

    public function setMeetingPointEn(?string $meetingPointEn): self
    {
        $this->meetingPointEn = $meetingPointEn;

        return $this;
    }

    public function getIncluded(): ?string
    {
        return $this->included;
    }

    public function setIncluded(?string $included): self
    {
        $this->included = $included;

        return $this;
    }

    public function getIncludedEn(): ?string
    {
        return $this->includedEn;
    }

    public function setIncludedEn(?string $includedEn): self
    {
        $this->includedEn = $includedEn;

        return $this;
    }

    public function getNotIncluded(): ?string
    {
        return $this->notIncluded;
    }

    public function setNotIncluded(?string $notIncluded): self
    {
        $this->notIncluded = $notIncluded;

        return $this;
    }

    public function getNotIncludedEn(): ?string
    {
        return $this->notIncludedEn;
    }

    public function setNotIncludedEn(?string $notIncludedEn): self
    {
        $this->notIncludedEn = $notIncludedEn;

        return $this;
    }

    public function getEquipmentToBring(): ?string
    {
        return $this->equipmentToBring;
    }

    public function setEquipmentToBring(?string $equipmentToBring): self
    {
        $this->equipmentToBring = $equipmentToBring;

        return $this;
    }

    public function getEquipmentToBringEn(): ?string
    {
        return $this->equipmentToBringEn;
    }

    public function setEquipmentToBringEn(?string $equipmentToBringEn): self
    {
        $this->equipmentToBringEn = $equipmentToBringEn;

        return $this;
    }

    public function getEquipmentProvided(): ?string
    {
        return $this->equipmentProvided;
    }

    public function setEquipmentProvided(?string $equipmentProvided): self
    {
        $this->equipmentProvided = $equipmentProvided;

        return $this;
    }

    public function getEquipmentProvidedEn(): ?string
    {
        return $this->equipmentProvidedEn;
    }

    public function setEquipmentProvidedEn(?string $equipmentProvidedEn): self
    {
        $this->equipmentProvidedEn = $equipmentProvidedEn;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceNote(): ?string
    {
        return $this->priceNote;
    }

    public function setPriceNote(?string $priceNote): self
    {
        $this->priceNote = $priceNote;

        return $this;
    }

    public function getPriceNoteEn(): ?string
    {
        return $this->priceNoteEn;
    }

    public function setPriceNoteEn(?string $priceNoteEn): self
    {
        $this->priceNoteEn = $priceNoteEn;

        return $this;
    }

    public function getLevel(): ?OutingLevel
    {
        return $this->level;
    }

    public function setLevel(?OutingLevel $level): self
    {
        $this->level = $level;

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

    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function isFeaturedOnHome(): bool
    {
        return $this->featuredOnHome;
    }

    public function setFeaturedOnHome(bool $featuredOnHome): self
    {
        $this->featuredOnHome = $featuredOnHome;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): self
    {
        $this->imageFile = $imageFile;
        // un champ doit changer pour que Doctrine déclenche l'upload Vich
        if (null !== $imageFile) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    public function getImageAlt(): ?string
    {
        return $this->imageAlt;
    }

    public function setImageAlt(?string $imageAlt): self
    {
        $this->imageAlt = $imageAlt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
