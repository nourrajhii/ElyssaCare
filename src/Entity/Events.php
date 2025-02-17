<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: EventsRepository::class)]
#[Vich\Uploadable]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le titre ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(
        min: 10,
        max: 255,
        minMessage: "La description doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La description ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le lieu est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le lieu doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le lieu ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $lieu = null;

    #[ORM\Column(type: "datetime")]
    #[Assert\NotNull(message: "La date ne peut pas être vide.")]
    #[Assert\GreaterThanOrEqual("today", message: "La date doit être aujourd’hui ou plus tard.")]
#[Assert\Type(type: "\DateTimeInterface", message: "Veuillez entrer une date et une heure valides.")]


private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Sponsor::class, inversedBy: 'events')]
    #[ORM\JoinColumn(name: 'id_sponsor', referencedColumnName: 'id')]
    #[Assert\NotNull(message: "Le sponsor est requis")]
    private ?Sponsor $idSponsor = null;

    // Getters and Setters for bidirectional relationship with Sponsor
    public function getIdSponsor(): ?Sponsor
    {
        return $this->idSponsor;
    }

    public function setIdSponsor(?Sponsor $idSponsor): self
    {
        $this->idSponsor = $idSponsor;

        return $this;
    }

    // Getters and Setters for other fields
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    #[Vich\UploadableField(mapping: "event_images", fileNameProperty: "image")]

    #[Assert\File(
        maxSize: "2M",
        maxSizeMessage: "L'image ne doit pas dépasser 2 Mo.",
        mimeTypes: ["image/jpeg", "image/png", "image/webp"],
        mimeTypesMessage: "Seuls les formats JPEG, PNG et WebP sont autorisés."
    )]
    private ?\Symfony\Component\HttpFoundation\File\File $imageFile = null;

    public function getImageFile(): ?\Symfony\Component\HttpFoundation\File\File
    {
        return $this->imageFile;
    }

    public function setImageFile(?\Symfony\Component\HttpFoundation\File\File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    private ?\DateTimeInterface $updatedAt = null;

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        if ($this->imageFile) {
            $this->updatedAt = new \DateTime();
        }
    }
}