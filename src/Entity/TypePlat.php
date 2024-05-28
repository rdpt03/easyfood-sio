<?php

namespace App\Entity;

use App\Repository\TypePlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypePlatRepository::class)]
class TypePlat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleTypePlat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $iconeTypePlat = null;

    #[ORM\OneToMany(mappedBy: 'leTypePlat', targetEntity: Plat::class)]
    private Collection $lesPlats;

    public function __construct()
    {
        $this->lesPlats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getLibelleTypePlat(): ?string
    {
        return $this->libelleTypePlat;
    }

    public function setLibelleTypePlat(string $libelleTypePlat): static
    {
        $this->libelleTypePlat = $libelleTypePlat;

        return $this;
    }

    public function getIconeTypePlat(): ?string
    {
        return $this->iconeTypePlat;
    }

    public function setIconeTypePlat(?string $iconeTypePlat): static
    {
        $this->iconeTypePlat = $iconeTypePlat;

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
            $lesPlat->setLeTypePlat($this);
        }

        return $this;
    }

    public function removeLesPlat(Plat $lesPlat): static
    {
        if ($this->lesPlats->removeElement($lesPlat)) {
            // set the owning side to null (unless already changed)
            if ($lesPlat->getLeTypePlat() === $this) {
                $lesPlat->setLeTypePlat(null);
            }
        }

        return $this;
    }
}
