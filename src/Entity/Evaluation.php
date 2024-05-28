<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $qualiteNourriture = null;

    #[ORM\Column]
    private ?int $respectRecette = null;

    #[ORM\Column]
    private ?int $esthetique = null;

    #[ORM\Column]
    private ?int $cout = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column]
    private ?bool $visibilite = null;

    #[ORM\ManyToOne(inversedBy: 'lesEvaluations',targetEntity:Restaurant::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $leRestaurant = null;

    #[ORM\ManyToOne(inversedBy: 'lesEvaluations',targetEntity:Utilisateur::class)]
    private ?Utilisateur $lUtilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQualiteNourriture(): ?int
    {
        return $this->qualiteNourriture;
    }

    public function setQualiteNourriture(?int $qualiteNourriture): static
    {
        $this->qualiteNourriture = $qualiteNourriture;

        return $this;
    }

    public function getRespectRecette(): ?int
    {
        return $this->respectRecette;
    }

    public function setRespectRecette(int $respectRecette): static
    {
        $this->respectRecette = $respectRecette;

        return $this;
    }

    public function getEsthetique(): ?int
    {
        return $this->esthetique;
    }

    public function setEsthetique(int $esthetique): static
    {
        $this->esthetique = $esthetique;

        return $this;
    }

    public function getCout(): ?int
    {
        return $this->cout;
    }

    public function setCout(int $cout): static
    {
        $this->cout = $cout;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function isVisibilite(): ?bool
    {
        return $this->visibilite;
    }

    public function setVisibilite(bool $visibilite): static
    {
        $this->visibilite = $visibilite;

        return $this;
    }

    public function getLeRestaurant(): ?Restaurant
    {
        return $this->leRestaurant;
    }

    public function setLeRestaurant(?Restaurant $leRestaurant): static
    {
        $this->leRestaurant = $leRestaurant;

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
}
