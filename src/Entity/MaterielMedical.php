<?php

namespace App\Entity;

use App\Repository\MaterielMedicalRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MaterielMedicalRepository::class)]
class MaterielMedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "📌 Le nom du matériel est obligatoire. Merci de le renseigner.")]
    #[Assert\Length(
        min: 3,
        minMessage: "⚠️ Le nom doit contenir au moins {{ limit }} caractères. Ajoutez plus de détails."
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Image(
        maxSize: "2M",
        mimeTypes: ["image/jpeg", "image/png", "image/webp"],
        mimeTypesMessage: "🖼️ Format invalide ! Veuillez télécharger une image en JPG, PNG ou WEBP (max 2Mo)."
    )]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "📝 Une description est requise. Merci de fournir plus de détails.")]
    #[Assert\Length(
        min: 10,
        minMessage: "📖 La description doit contenir au moins {{ limit }} caractères. Soyez plus précis !"
    )]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "💰 Le prix est obligatoire. Merci de le renseigner.")]
    #[Assert\Positive(message: " Le prix doit être un nombre positif. Vérifiez votre saisie.")]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "⚙️ Veuillez renseigner le statut du matériel.")]
    #[Assert\Choice(choices: ["Disponible", "Indisponible"], message: "❗ Le statut doit être 'Disponible' ou 'Indisponible'.")]
    private ?string $statut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }
}
