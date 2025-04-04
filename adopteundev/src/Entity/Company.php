<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $nom = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(length: 100)]
    private ?string $ville = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localisation = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Poste>
     */
    #[ORM\OneToMany(targetEntity: Poste::class, mappedBy: 'company')]
    private Collection $postes;

    /**
     * @var Collection<int, Developer>
     */
    #[ORM\ManyToMany(targetEntity: Developer::class)]
    #[ORM\JoinTable(name: 'developer_saved')]
    private Collection $developerSaved;

    public function __construct()
    {
        $this->postes = new ArrayCollection();
        $this->developerSaved = new ArrayCollection();
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

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

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;

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

    /**
     * @return Collection<int, Poste>
     */
    public function getPostes(): Collection
    {
        return $this->postes;
    }

    public function addPoste(Poste $poste): static
    {
        if (!$this->postes->contains($poste)) {
            $this->postes->add($poste);
            $poste->setCompany($this);
        }

        return $this;
    }

    // count poste
    public function countPostes(): int
    {
        return count($this->postes);
    }

    public function removePoste(Poste $poste): static
    {
        if ($this->postes->removeElement($poste)) {
            // set the owning side to null (unless already changed)
            if ($poste->getCompany() === $this) {
                $poste->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Developer>
     */
    public function getDeveloperSaved(): Collection
    {
        return $this->developerSaved;
    }

    public function addDeveloperSaved(Developer $developerSaved): static
    {
        if (!$this->developerSaved->contains($developerSaved)) {
            $this->developerSaved->add($developerSaved);
        }

        return $this;
    }

    

    public function removeDeveloperSaved(Developer $developerSaved): static
    {
        $this->developerSaved->removeElement($developerSaved);

        return $this;
    }
}
