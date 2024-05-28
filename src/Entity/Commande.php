<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:'datetime')]
    private $dateCommande = null;
    
    #[ORM\Column(type:'datetime',nullable: true)]
    private $dateLivrCommande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaireClientCommande = null;


    #[ORM\Column(length: 255)]
    private ?string $modeReglementCommande = null;

    #[ORM\OneToMany(mappedBy: 'uneCommande', targetEntity: QuantitePlat::class)]
    private Collection $lesQuantitesPlats;
    
    #[ORM\ManyToOne(inversedBy: 'lesCommandes', targetEntity: Utilisateur::class)]
    private Utilisateur $lUtilisateur;

    public function __construct()
    {
        $this->lesQuantitesPlats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDateCommande() {
        return $this->dateCommande;
    }

    public function getDateLivrCommande() {
        return $this->dateLivrCommande;
    }

    public function setDateCommande($dateCommande): void {
        $this->dateCommande = $dateCommande;
    }

    public function setDateLivrCommande($dateLivrCommande): void {
        $this->dateLivrCommande = $dateLivrCommande;
    }

    
    public function getCommentaireClientCommande(): ?string
    {
        return $this->commentaireClientCommande;
    }

    public function setCommentaireClientCommande(?string $commentaireClientCommande): static
    {
        $this->commentaireClientCommande = $commentaireClientCommande;

        return $this;
    }


    public function getModeReglementCommande(): ?string
    {
        return $this->modeReglementCommande;
    }

    public function setModeReglementCommande(string $modeReglementCommande): static
    {
        $this->modeReglementCommande = $modeReglementCommande;

        return $this;
    }

    /**
     * @return Collection<int, QuantitePlat>
     */
    public function getLesQuantitesPlats(): Collection
    {
        return $this->lesQuantitesPlats;
    }
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setLesQuantitesPlats(Collection $lesQuantitesPlats): void {
        $this->lesQuantitesPlats = $lesQuantitesPlats;
    }

    
    public function addLesQuantitesPlat(QuantitePlat $lesQuantitesPlat): static
    {
        if (!$this->lesQuantitesPlats->contains($lesQuantitesPlat)) {
            $this->lesQuantitesPlats->add($lesQuantitesPlat);
            $lesQuantitesPlat->setUneCommande($this);
        }

        return $this;
    }

    public function removeLesQuantitesPlat(QuantitePlat $lesQuantitesPlat): static
    {
        if ($this->lesQuantitesPlats->removeElement($lesQuantitesPlat)) {
            // set the owning side to null (unless already changed)
            if ($lesQuantitesPlat->getUneCommande() === $this) {
                $lesQuantitesPlat->setUneCommande(null);
            }
        }

        return $this;
    }
    
    public function getUnUtilisateur(): Utilisateur {
        return $this->lUtilisateur;
    }

    public function setUnUtilisateur(Utilisateur $unUtilisateur): void {
        $this->lUtilisateur = $unUtilisateur;
    }


}
