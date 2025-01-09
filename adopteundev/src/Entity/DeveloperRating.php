<?php

namespace App\Entity;

use App\Repository\DeveloperRatingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeveloperRatingRepository::class)]
class DeveloperRating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Developer $rateDeveloper = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Developer $ratingDeveloper = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $rating = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRateDeveloper(): ?Developer
    {
        return $this->rateDeveloper;
    }

    public function setRateDeveloper(?Developer $rateDeveloper): static
    {
        $this->rateDeveloper = $rateDeveloper;

        return $this;
    }

    public function getRatingDeveloper(): ?Developer
    {
        return $this->ratingDeveloper;
    }

    public function setRatingDeveloper(?Developer $ratingDeveloper): static
    {
        $this->ratingDeveloper = $ratingDeveloper;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
