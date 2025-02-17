<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du patient est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $patient_nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email du patient est obligatoire.")]
    #[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas valide.")]
    private ?string $patient_email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le téléphone est obligatoire.")]
    #[Assert\Regex(pattern: "/^\d{8}$/", message: "Le numéro de téléphone doit contenir 8 chiffres.")]
    private ?string $patient_telephone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date du rendez-vous est obligatoire.")]
    #[Assert\Type("\DateTimeInterface", message: "La date doit être valide.")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank(message: "L'heure du rendez-vous est obligatoire.")]
    #[Assert\Type("\DateTimeInterface", message: "L'heure doit être valide.")]
    private ?\DateTimeInterface $heure = null;

    #[ORM\Column(length: 255)]
    #[Assert\Choice(choices: ['En attente', 'Confirmé', 'Annulé'], message: "L'état doit être 'En attente', 'Confirmé' ou 'Annulé'.")]
    private ?string $etat = null;

    //#[ORM\ManyToMany(targetEntity: Analyse::class, mappedBy: 'rendezVous')]
    //private Collection $analyses;

    #[ORM\ManyToOne(inversedBy: 'rendezVouses')]
    #[ORM\JoinColumn(name: 'laboratoire_id', referencedColumnName: 'id', onDelete: 'CASCADE', nullable: true)]
    private ?Laboratoire $laboratoire = null;

    #[ORM\ManyToMany(targetEntity: Analyse::class, inversedBy: 'rendezVouses')]
    private Collection $analyse;

    public function __toString(): string
    {
        return $this->getPatientNom() . ' - ' . $this->getDate()->format('d/m/Y');
    }

    public function __construct()
    {
        $this->analyses = new ArrayCollection();
        $this->analyse = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatientNom(): ?string
    {
        return $this->patient_nom;
    }

    public function setPatientNom(string $patient_nom): static
    {
        $this->patient_nom = $patient_nom;

        return $this;
    }

    public function getPatientEmail(): ?string
    {
        return $this->patient_email;
    }

    public function setPatientEmail(string $patient_email): static
    {
        $this->patient_email = $patient_email;

        return $this;
    }

    public function getPatientTelephone(): ?string
    {
        return $this->patient_telephone;
    }

    public function setPatientTelephone(string $patient_telephone): static
    {
        $this->patient_telephone = $patient_telephone;

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

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

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
            $analysis->addRendezVou($this);
        }

        return $this;
    }

    public function removeAnalysis(Analyse $analysis): static
    {
        if ($this->analyses->removeElement($analysis)) {
            $analysis->removeRendezVou($this);
        }

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
     * @return Collection<int, Analyse>
     */
    public function getAnalyse(): Collection
    {
        return $this->analyse;
    }

    public function addAnalyse(Analyse $analyse): static
    {
        if (!$this->analyse->contains($analyse)) {
            $this->analyse->add($analyse);
        }

        return $this;
    }

    public function removeAnalyse(Analyse $analyse): static
    {
        $this->analyse->removeElement($analyse);

        return $this;
    }

    
}
