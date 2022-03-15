<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BlogRepository;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 * @Vich\Uploadable
 */
class Blog
{

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
        $this->blogImages = new ArrayCollection();
        $this->createdAt = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null;
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="blog_images", fileNameProperty="image")
     * @var File|null
     */
    private $imageFile;



    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $englishTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $englishContent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     */
    private $comments;


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

    // public function getVirtualFilename()
    // {
    //     //Set path for easyadmin
    //     return realpath(__DIR__.'/../../public/uploads/'.$this->image1);
    // }

    // public function setVirtualFilename($image1)
    // {
    //     //Only keep last part of filepath
    //     $this->setImage1(basename($image1));
    // }

    public function __toString()
    {
        return $this->title;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function addCreatedAt(Comment $createdAt): self
    {
        if (!$this->createdAt->contains($createdAt)) {
            $this->createdAt[] = $createdAt;
            $createdAt->setPost($this);
        }

        return $this;
    }

    public function removeCreatedAt(Comment $createdAt): self
    {
        if ($this->createdAt->removeElement($createdAt)) {
            // set the owning side to null (unless already changed)
            if ($createdAt->getPost() === $this) {
                $createdAt->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }
    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->title);
    }
}
