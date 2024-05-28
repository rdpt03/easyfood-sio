<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomPlat = null;

    #[ORM\Column]
    private ?float $prixFournisseurPlat = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixClientPlat = null;

    #[ORM\Column]
    private ?bool $platVisible = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoPlat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descriptionPlat = null;

    #[ORM\ManyToOne(inversedBy: 'lesPlats',targetEntity:"Restaurant")]
    private ?Restaurant $leRestaurant = null;

    #[ORM\ManyToOne(inversedBy: 'lesPlats',targetEntity:"TypePlat")]
    private ?TypePlat $leTypePlat = null;

    #[ORM\OneToMany(mappedBy: 'lePlat', targetEntity:"QuantitePlat")]
    private Collection $lesQuantitesPlats;

    public function __construct()
    {
        $this->lesQuantitesPlats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPlat(): ?string
    {
        return $this->nomPlat;
    }

    public function setNomPlat(string $nomPlat): static
    {
        $this->nomPlat = $nomPlat;

        return $this;
    }

    public function getPrixFournisseurPlat(): ?float
    {
        return $this->prixFournisseurPlat;
    }

    public function setPrixFournisseurPlat(float $prixFournisseurPlat): static
    {
        $this->prixFournisseurPlat = $prixFournisseurPlat;

        return $this;
    }

    public function getPrixClientPlat(): ?float
    {
        return $this->prixClientPlat;
    }

    public function setPrixClientPlat(?float $prixClientPlat): static
    {
        $this->prixClientPlat = $prixClientPlat;

        return $this;
    }

    public function isPlatVisible(): ?bool
    {
        return $this->platVisible;
    }

    public function setPlatVisible(bool $platVisible): static
    {
        $this->platVisible = $platVisible;

        return $this;
    }

    public function getPhotoPlat(): ?string
    {
        return $this->photoPlat;
    }

    public function setPhotoPlat(?string $photoPlat): static
    {
        $this->photoPlat = $photoPlat;

        return $this;
    }

    public function getDescriptionPlat(): ?string
    {
        return $this->descriptionPlat;
    }

    public function setDescriptionPlat(?string $descriptionPlat): static
    {
        $this->descriptionPlat = $descriptionPlat;

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

    public function getLeTypePlat(): ?TypePlat
    {
        return $this->leTypePlat;
    }

    public function setLeTypePlat(?TypePlat $leTypePlat): static
    {
        $this->leTypePlat = $leTypePlat;

        return $this;
    }

    /**
     * @return Collection<int, QuantitePlat>
     */
    public function getLesQuantitesPlats(): Collection
    {
        return $this->lesQuantitesPlats;
    }

    public function addLesQuantitesPlat(QuantitePlat $lesQuantitesPlat): static
    {
        if (!$this->lesQuantitesPlats->contains($lesQuantitesPlat)) {
            $this->lesQuantitesPlats->add($lesQuantitesPlat);
            $lesQuantitesPlat->setLePlat($this);
        }

        return $this;
    }

    public function removeLesQuantitesPlat(QuantitePlat $lesQuantitesPlat): static
    {
        if ($this->lesQuantitesPlats->removeElement($lesQuantitesPlat)) {
            // set the owning side to null (unless already changed)
            if ($lesQuantitesPlat->getLePlat() === $this) {
                $lesQuantitesPlat->setLePlat(null);
            }
        }

        return $this;
    }
}
