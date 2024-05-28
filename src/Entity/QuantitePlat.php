<?php

namespace App\Entity;

use App\Repository\QuantitePlatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuantitePlatRepository::class)]
class QuantitePlat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = 1;

    #[ORM\ManyToOne(inversedBy: 'lesQuantitesPlats',targetEntity:"Plat")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plat $lePlat = null;

    #[ORM\ManyToOne(inversedBy: 'lesQuantitesPlats',targetEntity:"Commande")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $uneCommande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getLePlat(): ?Plat
    {
        return $this->lePlat;
    }

    public function setLePlat(?Plat $lePlat): static
    {
        $this->lePlat = $lePlat;

        return $this;
    }

    public function getUneCommande(): ?Commande
    {
        return $this->uneCommande;
    }

    public function setUneCommande(?Commande $uneCommande): static
    {
        $this->uneCommande = $uneCommande;

        return $this;
    }
}
