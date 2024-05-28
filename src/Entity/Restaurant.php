<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomRestaurant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numAdrRestaurant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rueAdrRestaurant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cpRestaurant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $villeRestaurant = null;

    #[ORM\Column(nullable: true)]
    private ?string $heureFermeture = null;

    #[ORM\Column(nullable: true)]
    private ?string $heureOuverture = null;

    #[ORM\Column(nullable: true)]
    private ?string $photoResto = null;

    #[ORM\OneToMany(mappedBy: 'leRestaurant', targetEntity: Plat::class)]
    private Collection $lesPlats;

    #[ORM\OneToMany(mappedBy: 'leRestaurant', targetEntity: Evaluation::class)]
    private Collection $lesEvaluations;

    #[ORM\ManyToOne(inversedBy: 'lesRestaurants',targetEntity:Utilisateur::class)]
    private ?Utilisateur $lUtilisateur = null;

    public function __construct()
    {
        $this->lesPlats = new ArrayCollection();
        $this->lesEvaluations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomRestaurant(): ?string
    {
        return $this->nomRestaurant;
    }

    public function setNomRestaurant(string $nomRestaurant): static
    {
        $this->nomRestaurant = $nomRestaurant;

        return $this;
    }

    public function getNumAdrRestaurant(): ?string
    {
        return $this->numAdrRestaurant;
    }

    public function setNumAdrRestaurant(?string $numAdrRestaurant): static
    {
        $this->numAdrRestaurant = $numAdrRestaurant;

        return $this;
    }

    public function getRueAdrRestaurant(): ?string
    {
        return $this->rueAdrRestaurant;
    }

    public function setRueAdrRestaurant(?string $rueAdrRestaurant): static
    {
        $this->rueAdrRestaurant = $rueAdrRestaurant;

        return $this;
    }

    public function getCpRestaurant(): ?string
    {
        return $this->cpRestaurant;
    }

    public function setCpRestaurant(?string $cpRestaurant): static
    {
        $this->cpRestaurant = $cpRestaurant;

        return $this;
    }

    public function getVilleRestaurant(): ?string
    {
        return $this->villeRestaurant;
    }

    public function setVilleRestaurant(?string $villeRestaurant): static
    {
        $this->villeRestaurant = $villeRestaurant;

        return $this;
    }

    public function getHeureFermeture(): ?string
    {
        return $this->heureFermeture;
    }

    public function setHeureFermeture(?int $heureFermeture): static
    {
        $this->heureFermeture = $heureFermeture;

        return $this;
    }

    public function getHeureOuverture(): ?string
    {
        return $this->heureOuverture;
    }

    public function setHeureOuverture(?int $heureOuverture): static
    {
        $this->heureOuverture = $heureOuverture;

        return $this;
    }

    public function getPhotoResto(): ?string
    {
        return $this->photoResto;
    }

    public function setPhotoResto(?string $pr): static
    {
        $this->photoResto = $pr;

        return $this;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getLesPlats(): Collection
    {
        return $this->lesPlats;
    }

    public function addLesPlat(Plat $lesPlat): static
    {
        if (!$this->lesPlats->contains($lesPlat)) {
            $this->lesPlats->add($lesPlat);
            $lesPlat->setLeRestaurant($this);
        }

        return $this;
    }

    public function removeLesPlat(Plat $lesPlat): static
    {
        if ($this->lesPlats->removeElement($lesPlat)) {
            // set the owning side to null (unless already changed)
            if ($lesPlat->getLeRestaurant() === $this) {
                $lesPlat->setLeRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getLesEvaluations(): Collection
    {
        return $this->lesEvaluations;
    }

    public function addLesEvaluation(Evaluation $lesEvaluation): static
    {
        if (!$this->lesEvaluations->contains($lesEvaluation)) {
            $this->lesEvaluations->add($lesEvaluation);
            $lesEvaluation->setLeRestaurant($this);
        }

        return $this;
    }

    public function removeLesEvaluation(Evaluation $lesEvaluation): static
    {
        if ($this->lesEvaluations->removeElement($lesEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($lesEvaluation->getLeRestaurant() === $this) {
                $lesEvaluation->setLeRestaurant(null);
            }
        }

        return $this;
    }

    public function getLUtilisateur(): ?Utilisateur
    {
        return $this->lUtilisateur;
    }

    public function setLUtilisateur(?Utilisateur $lUtilisateur): static
    {
        $this->lUtilisateur = $lUtilisateur;

        return $this;
    }
    
    public function __toString() {
        return $this->nomRestaurant;
    }
}
