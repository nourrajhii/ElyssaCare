<?php

namespace App\Entity;

use App\Repository\MedicamentsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MedicamentsRepository::class)]
class Medicaments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "🌟 Le nom du médicament est requis. Veuillez le renseigner.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "🚨 Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "❗ Le nom ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "✍️ La description est obligatoire. Merci d'ajouter une brève description.")]
    #[Assert\Length(
        min: 10,
        max: 255,
        minMessage: "⚠️ La description doit comporter au moins {{ limit }} caractères.",
        maxMessage: "❗ La description ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "📌 Veuillez préciser la classe du médicament.")]
    private ?string $classe = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "💰 Le prix est requis. Veuillez entrer un montant.")]
    #[Assert\Positive(message: "🔢 Le prix doit être un nombre positif. Vérifiez votre saisie.")]
    private ?int $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message: "🖼️ L'URL fournie pour l'image n'est pas valide. Vérifiez l'adresse.")]
    private ?string $image = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): static
    {
        $this->classe = $classe;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

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
}
