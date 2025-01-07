<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SavedPostRepository;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: SavedPostRepository::class)]
class SavedPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Developer::class, inversedBy: 'savedPosts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Developer $developer = null;

    #[ORM\ManyToOne(targetEntity: Poste::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Poste $poste = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $savedAT = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $expiredAT = null;

    public function __construct()
    {
        $this->savedAT = new \DateTimeImmutable();
        //$this->expiredAT = $this->poste->getDateLimite();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): static
    {
        $this->poste = $poste;

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

    public function getExpiredAt(): ?\DateTimeImmutable
    {
        return $this->expiredAT;
    }

    public function setExpiredAt(\DateTimeImmutable $expiredAt): static
    {
        $this->expiredAT = $expiredAt;

        return $this;
    }
}
