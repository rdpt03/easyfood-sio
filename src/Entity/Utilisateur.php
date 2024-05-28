<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $mail = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomUtilisateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenomUtilisateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $villeUtilisateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rueUtilisateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cpUtilisateur = null;
    
    #[ORM\Column(nullable: true)]
    private ?bool $supprimer= false;

    #[ORM\Column(nullable: true)]
    private ?string $numRueUtilisateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoprofilUtilisateur = null;

    #[ORM\Column(nullable: true)]
    private ?int $noteEasyFood = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaireEasyFood = null;

    #[ORM\Column (nullable: true)]
    private ?bool $commentaireVisible = null;

    #[ORM\OneToMany(mappedBy: 'lUtilisateur', targetEntity: Restaurant::class)]
    private Collection $lesRestaurants;

    #[ORM\OneToMany(mappedBy: 'lUtilisateur', targetEntity: Evaluation::class)]
    private Collection $lesEvaluations;
    
    #[ORM\OneToMany(mappedBy: 'lUtilisateur', targetEntity: Commande::class)]
    private Collection $lesCommandes;

    #[ORM\ManyToOne(inversedBy: 'lesUtilisateurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeUtilisateur $leTypeUtilisateur = null;

    public function __construct()
    {
        $this->lesRestaurants = new ArrayCollection();
        $this->lesEvaluations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->mail;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles=[];
        $roles[]=$this->getLeTypeUtilisateur()->getLibelleTypeUtilisateur();
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nomUtilisateur;
    }

    public function setNomUtilisateur(string $nomUtilisateur): static
    {
        $this->nomUtilisateur = $nomUtilisateur;

        return $this;
    }

    public function getPrenomUtilisateur(): ?string
    {
        return $this->prenomUtilisateur;
    }

    public function setPrenomUtilisateur(string $prenomUtilisateur): static
    {
        $this->prenomUtilisateur = $prenomUtilisateur;

        return $this;
    }

    public function getVilleUtilisateur(): ?string
    {
        return $this->villeUtilisateur;
    }

    public function setVilleUtilisateur(?string $villeUtilisateur): static
    {
        $this->villeUtilisateur = $villeUtilisateur;

        return $this;
    }

    public function getRueUtilisateur(): ?string
    {
        return $this->rueUtilisateur;
    }

    public function setRueUtilisateur(?string $rueUtilisateur): static
    {
        $this->rueUtilisateur = $rueUtilisateur;

        return $this;
    }

    public function getCpUtilisateur(): ?string
    {
        return $this->cpUtilisateur;
    }

    public function setCpUtilisateur(?string $cpUtilisateur): static
    {
        $this->cpUtilisateur = $cpUtilisateur;

        return $this;
    }

    public function getNumRueUtilisateur(): ?int
    {
        return $this->numRueUtilisateur;
    }

    public function setNumRueUtilisateur(?int $numRueUtilisateur): static
    {
        $this->numRueUtilisateur = $numRueUtilisateur;

        return $this;
    }

    public function getPhotoprofilUtilisateur(): ?string
    {
        return $this->photoprofilUtilisateur;
    }

    public function setPhotoprofilUtilisateur(?string $photoprofilUtilisateur): static
    {
        $this->photoprofilUtilisateur = $photoprofilUtilisateur;

        return $this;
    }

    public function getNoteEasyFood(): ?int
    {
        return $this->noteEasyFood;
    }

    public function setNoteEasyFood(?int $noteEasyFood): static
    {
        $this->noteEasyFood = $noteEasyFood;

        return $this;
    }

    public function getCommentaireEasyFood(): ?string
    {
        return $this->commentaireEasyFood;
    }

    public function setCommentaireEasyFood(?string $commentaireEasyFood): static
    {
        $this->commentaireEasyFood = $commentaireEasyFood;

        return $this;
    }

    public function isCommentaireVisible(): ?bool
    {
        return $this->commentaireVisible;
    }

    public function setCommentaireVisible(bool $commentaireVisible): static
    {
        $this->commentaireVisible = $commentaireVisible;

        return $this;
    }
    
    public function getSupprimer(): ?bool {
        return $this->supprimer;
    }

    public function setSupprimer(?bool $supprimer): void {
        $this->supprimer = $supprimer;
    }

    
    /**
     * @return Collection<int, Restaurant>
     */
    public function getLesRestaurants(): Collection
    {
        return $this->lesRestaurants;
    }

    public function addLesRestaurant(Restaurant $lesRestaurant): static
    {
        if (!$this->lesRestaurants->contains($lesRestaurant)) {
            $this->lesRestaurants->add($lesRestaurant);
            $lesRestaurant->setLUtilisateur($this);
        }

        return $this;
    }

    public function removeLesRestaurant(Restaurant $lesRestaurant): static
    {
        if ($this->lesRestaurants->removeElement($lesRestaurant)) {
            // set the owning side to null (unless already changed)
            if ($lesRestaurant->getLUtilisateur() === $this) {
                $lesRestaurant->setLUtilisateur(null);
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
            $lesEvaluation->setLUtilisateur($this);
        }

        return $this;
    }

    public function removeLesEvaluation(Evaluation $lesEvaluation): static
    {
        if ($this->lesEvaluations->removeElement($lesEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($lesEvaluation->getLUtilisateur() === $this) {
                $lesEvaluation->setLUtilisateur(null);
            }
        }

        return $this;
    }

    public function getLeTypeUtilisateur(): ?TypeUtilisateur
    {
        return $this->leTypeUtilisateur;
    }

    public function setLeTypeUtilisateur(?TypeUtilisateur $leTypeUtilisateur): static
    {
        $this->leTypeUtilisateur = $leTypeUtilisateur;

        return $this;
    }
    
    public function getLesCommandes(): Collection {
        return $this->lesCommandes;
    }

    public function setLesCommandes(Collection $lesCommandes): void {
        $this->lesCommandes = $lesCommandes;
    }


}
