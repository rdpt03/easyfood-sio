<?php

namespace App\Entity;

use App\Repository\TypeUtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeUtilisateurRepository::class)]
class TypeUtilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleTypeUtilisateur = null;

    #[ORM\OneToMany(mappedBy: 'leTypeUtilisateur', targetEntity: Utilisateur::class)]
    private Collection $lesUtilisateurs;

    public function __construct()
    {
        $this->lesUtilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleTypeUtilisateur(): ?string
    {
        return $this->libelleTypeUtilisateur;
    }

    public function setLibelleTypeUtilisateur(string $libelleTypeUtilisateur): static
    {
        $this->libelleTypeUtilisateur = $libelleTypeUtilisateur;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getLesUtilisateurs(): Collection
    {
        return $this->lesUtilisateurs;
    }

    public function addLesUtilisateur(Utilisateur $lesUtilisateur): static
    {
        if (!$this->lesUtilisateurs->contains($lesUtilisateur)) {
            $this->lesUtilisateurs->add($lesUtilisateur);
            $lesUtilisateur->setLeTypeUtilisateur($this);
        }

        return $this;
    }

    public function removeLesUtilisateur(Utilisateur $lesUtilisateur): static
    {
        if ($this->lesUtilisateurs->removeElement($lesUtilisateur)) {
            // set the owning side to null (unless already changed)
            if ($lesUtilisateur->getLeTypeUtilisateur() === $this) {
                $lesUtilisateur->setLeTypeUtilisateur(null);
            }
        }

        return $this;
    }
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setLesUtilisateurs(Collection $lesUtilisateurs): void {
        $this->lesUtilisateurs = $lesUtilisateurs;
    }
    


}
