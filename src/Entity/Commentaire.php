<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le nom d'utilisateur ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le nom d'utilisateur ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $nom_utilisateur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: "La date ne peut pas être vide.")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le contenu du commentaire ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le contenu du commentaire ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $contenu = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "Le nombre de likes ne peut pas être vide.")]
    #[Assert\PositiveOrZero(message: "Le nombre de likes doit être un nombre positif ou nul.")]
    private ?int $nombre_like = null;

    // Relation ManyToOne avec Blog
    #[ORM\ManyToOne(targetEntity: Blog::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Blog $blog = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nom_utilisateur;
    }

    public function setNomUtilisateur(?string $nom_utilisateur): static
    {
        $this->nom_utilisateur = $nom_utilisateur;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getNombreLike(): ?int
    {
        return $this->nombre_like;
    }

    public function setNombreLike(?int $nombre_like): static
    {
        $this->nombre_like = $nombre_like;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): static
    {
        $this->blog = $blog;

        return $this;
    }
}
