<?php

namespace App\Entity;

use App\Repository\DeveloperRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeveloperRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_MOBILE', fields: ['mobile'])]
class Developer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $nom = null;

    #[ORM\Column(length: 180)]
    private ?string $prenom = null;

    #[ORM\Column(length: 10)]
    private ?string $mobile = null;

    #[ORM\Column(nullable: true)]
    private ?float $salaireMin = null;

    #[ORM\Column]
    private ?bool $isDisponible = null;

    #[ORM\Column(nullable: true)]
    private ?int $experience = null;

    #[ORM\Column(length: 180)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $biographie = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'developer')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $cat = null;

    #[ORM\Column]
    private ?bool $mobileVisible = null;

    #[ORM\Column]
    private ?bool $salaireVisible = null;

    /**
     * @var Collection<int, Cv>
     */
    #[ORM\OneToMany(targetEntity: Cv::class, mappedBy: 'developer')]
    private Collection $cvs;
    
    /**
     * @var Collection<int, Candidature>
     */
    #[ORM\OneToMany(targetEntity: Candidature::class, mappedBy: 'developer')]
    private Collection $candidatures;
  
    /**
     * @var Collection<int, Technologie>
     */
    #[ORM\ManyToMany(targetEntity: Technologie::class, inversedBy: 'developers')]
    private Collection $technologie;

    
    #[ORM\OneToMany(mappedBy: 'developer', targetEntity: SavedPost::class, cascade: ['persist', 'remove'])]
    private Collection $savedPosts;

    // #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?Categorie $cat = null;

    public function __construct()
    {
        $this->mobileVisible = true;
        $this->salaireVisible = true;
        $this->isDisponible = true;
        $this->cvs = new ArrayCollection();
        $this->technologie = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->savedPosts = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getSalaireMin(): ?float
    {
        return $this->salaireMin;
    }

    public function setSalaireMin(?float $salaireMin): static
    {
        $this->salaireMin = $salaireMin;

        return $this;
    }

    public function isDisponible(): ?bool
    {
        return $this->isDisponible;
    }

    public function setDisponible(bool $isDisponible): static
    {
        $this->isDisponible = $isDisponible;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(?int $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(?string $biographie): static
    {
        $this->biographie = $biographie;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCat(): ?Categorie
    {
        return $this->cat;
    }

    public function setCat(Categorie $cat): static
    {
        $this->cat = $cat;

        return $this;
    }

    public function isMobileVisible(): ?bool
    {
        return $this->mobileVisible;
    }

    public function setMobileVisible(bool $mobileVisible): static
    {
        $this->mobileVisible = $mobileVisible;

        return $this;
    }

    public function isSalaireVisible(): ?bool
    {
        return $this->salaireVisible;
    }

    public function setSalaireVisible(bool $salaireVisible): static
    {
        $this->salaireVisible = $salaireVisible;

        return $this;
    }

    /**
     * @return Collection<int, Cv>
     */
    public function getCvs(): Collection
    {
        return $this->cvs;
    }

    public function addCv(Cv $cv): static
    {
        if (!$this->cvs->contains($cv)) {
            $this->cvs->add($cv);
            $cv->setDeveloper($this);
        }

        return $this;
    }

    public function removeCv(Cv $cv): static
    {
        if ($this->cvs->removeElement($cv)) {
            // set the owning side to null (unless already changed)
            if ($cv->getDeveloper() === $this) {
                $cv->setDeveloper(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Technologie>
     */
    public function getTechnologie(): Collection
    {
        return $this->technologie;
    }

    public function addTechnologie(Technologie $technologie): static
    {
        if (!$this->technologie->contains($technologie)) {
            $this->technologie->add($technologie);
        }

        return $this;
    }

    public function removeTechnologie(Technologie $technologie): static
    {
        $this->technologie->removeElement($technologie);

        return $this;
    }
    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): static
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setDeveloper($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getDeveloper() === $this) {
                $candidature->setDeveloper(null);
            }
        }

        return $this;
    }
        
    public function getSavedPosts(): Collection
    {
        return $this->savedPosts;
    }

    public function addSavedPost(SavedPost $savedPost): static
    {
        if (!$this->savedPosts->contains($savedPost)) {
            $this->savedPosts->add($savedPost);
            $savedPost->setDeveloper($this);
        }

        return $this;
    }

    public function removeSavedPost(SavedPost $savedPost): static
    {
        if ($this->savedPosts->removeElement($savedPost)) {
            if ($savedPost->getDeveloper() === $this) {
                $savedPost->setDeveloper(null);
            }
        }

        return $this;
    }

}