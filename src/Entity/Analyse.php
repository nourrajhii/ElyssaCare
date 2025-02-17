<?php

namespace App\Entity;

use App\Repository\AnalyseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Validator\Constraints as Assert;  // Importation des contraintes de validation

#[ORM\Entity(repositoryClass: AnalyseRepository::class)]
class Analyse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de l\'analyse est obligatoire')]  // Contrôle pour s'assurer que le champ n'est pas vide
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le nom doit contenir au moins {{ limit }} caractères', maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères')]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description est obligatoire')]  // Contrôle de la description
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    #[Assert\NotBlank(message: 'Le prix est obligatoire')]  // Contrôle du prix
    #[Assert\Positive(message: 'Le prix doit être un nombre positif')]  // Contrôle de positivité pour le prix
    private ?string $prix = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le type d\'analyse est obligatoire')]  // Contrôle du type d'analyse
    private ?string $type_analyse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le statut est obligatoire')]  // Contrôle du statut
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'La date de création est obligatoire')]  // Contrôle de la date de création
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'La date de mise à jour est obligatoire')]  // Contrôle de la date de mise à jour
    private ?\DateTimeInterface $date_mise_a_jour = null;


    #[ORM\ManyToOne(inversedBy: 'analyses')]
    private ?Laboratoire $laboratoire = null;

    #[ORM\ManyToMany(targetEntity: RendezVous::class, inversedBy: 'analyses')]
    private Collection $rendezVous;

    #[ORM\ManyToMany(targetEntity: RendezVous::class, mappedBy: 'analyse')]
    private Collection $rendezVouses;

    public function __construct()
    {
        $this->rendezVous = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
    }

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

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getTypeAnalyse(): ?string
    {
        return $this->type_analyse;
    }

    public function setTypeAnalyse(string $type_analyse): static
    {
        $this->type_analyse = $type_analyse;

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateMiseAJour(): ?\DateTimeInterface
    {
        return $this->date_mise_a_jour;
    }

    public function setDateMiseAJour(\DateTimeInterface $date_mise_a_jour): static
    {
        $this->date_mise_a_jour = $date_mise_a_jour;

        return $this;
    }

    public function getLaboratoire(): ?Laboratoire
    {
        return $this->laboratoire;
    }

    public function setLaboratoire(?Laboratoire $laboratoire): static
    {
        $this->laboratoire = $laboratoire;

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVous(): Collection
    {
        return $this->rendezVous;
    }

    public function addRendezVou(RendezVous $rendezVou): static
    {
        if (!$this->rendezVous->contains($rendezVou)) {
            $this->rendezVous->add($rendezVou);
        }

        return $this;
    }

    public function removeRendezVou(RendezVous $rendezVou): static
    {
        $this->rendezVous->removeElement($rendezVou);

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVouses(): Collection
    {
        return $this->rendezVouses;
    }

    public function addRendezVouse(RendezVous $rendezVouse): static
    {
        if (!$this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses->add($rendezVouse);
            $rendezVouse->addAnalyse($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): static
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            $rendezVouse->removeAnalyse($this);
        }

        return $this;
    }

    public function __toString(): string
{
    return $this->nom ?? 'Analyse';
}

}
