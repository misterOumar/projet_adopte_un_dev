<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SavedDeveloperRepository;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: SavedDeveloperRepository::class)]
class SavedDeveloper
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'savedDevelopers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\ManyToOne(targetEntity: Developer::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Developer $developer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $savedAT = null;

    public function __construct()
    {
        $this->savedAT = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getDeveloper(): ?Developer
    {
        return $this->developer;
    }

    public function setDeveloper(?Developer $developer): static
    {
        $this->developer = $developer;

        return $this;
    }

    public function getsavedAt(): ?\DateTimeImmutable
    {
        return $this->savedAT;
    }

    public function setsavedAt(\DateTimeImmutable $savedAT): static
    {
        $this->savedAT = $savedAT;

        return $this;
    }
}
