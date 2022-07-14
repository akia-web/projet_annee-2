<?php

namespace App\Entity;

use App\Repository\AnnoncesFollowRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnoncesFollowRepository::class)]
class AnnoncesFollow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'annoncesFollows')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Annonces::class, inversedBy: 'annoncesFollows')]
    #[ORM\JoinColumn(nullable: false)]
    private $Annonces;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAnnonces(): ?Annonces
    {
        return $this->Annonces;
    }

    public function setAnnonces(?Annonces $Annonces): self
    {
        $this->Annonces = $Annonces;

        return $this;
    }
}
