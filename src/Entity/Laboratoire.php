<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\LaboratoireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: LaboratoireRepository::class)]
class Laboratoire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du laboratoire est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $nom_laboratoire = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'adresse ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^\+?[0-9]{8,15}$/",
        message: "Le numéro de téléphone doit être valide."
    )]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "Veuillez saisir une adresse email valide.")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le responsable est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le nom du responsable doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom du responsable ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $responsable = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La spécialité est obligatoire.")]
    private ?string $specialite = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'horaire d'ouverture est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/",
        message: "L'heure d'ouverture doit être au format HH:MM (ex: 08:30)."
    )]
    private ?string $horaire_ouverture = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'horaire de fermeture est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/",
        message: "L'heure de fermeture doit être au format HH:MM (ex: 17:00)."
    )]
    private ?string $horaire_fermeture = null;

    #[ORM\OneToMany(targetEntity: Analyse::class, mappedBy: 'laboratoire', cascade: ['remove'])]
    private Collection $analyses;

    #[ORM\OneToMany(targetEntity: RendezVous::class, mappedBy: 'laboratoire', cascade: ['remove'])]
    private Collection $rendezVouses;

    public function __construct()
    {
        $this->analyses = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLaboratoire(): ?string
    {
        return $this->nom_laboratoire;
    }

    public function setNomLaboratoire(string $nom_laboratoire): static
    {
        $this->nom_laboratoire = $nom_laboratoire;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(string $responsable): static
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getHoraireOuverture(): ?string
    {
        return $this->horaire_ouverture;
    }

    public function setHoraireOuverture(string $horaire_ouverture): static
    {
        $this->horaire_ouverture = $horaire_ouverture;

        return $this;
    }

    public function getHoraireFermeture(): ?string
    {
        return $this->horaire_fermeture;
    }

    public function setHoraireFermeture(string $horaire_fermeture): static
    {
        $this->horaire_fermeture = $horaire_fermeture;

        return $this;
    }

    /**
     * @return Collection<int, Analyse>
     */
    public function getAnalyses(): Collection
    {
        return $this->analyses;
    }

    public function addAnalysis(Analyse $analysis): static
    {
        if (!$this->analyses->contains($analysis)) {
            $this->analyses->add($analysis);
            $analysis->setLaboratoire($this);
        }

        return $this;
    }

    public function removeAnalysis(Analyse $analysis): static
    {
        if ($this->analyses->removeElement($analysis)) {
            if ($analysis->getLaboratoire() === $this) {
                $analysis->setLaboratoire(null);
            }
        }

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
            $rendezVouse->setLaboratoire($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): static
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            if ($rendezVouse->getLaboratoire() === $this) {
                $rendezVouse->setLaboratoire(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom_laboratoire ?? 'Laboratoire';
    }
}
