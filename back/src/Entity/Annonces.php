<?php

namespace App\Entity;

use App\Repository\AnnoncesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnoncesRepository::class)]
class Annonces
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $images;

    #[ORM\ManyToOne(targetEntity: Categories::class, inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private $categorie;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'string', length: 255)]
    private $adresse;

    #[ORM\Column(type: 'integer')]
    private $codepostal;

    #[ORM\Column(type: 'string', length: 255)]
    private $ville;

    #[ORM\OneToMany(mappedBy: 'Annonces', targetEntity: AnnoncesFollow::class, orphanRemoval: true)]
    private $annoncesFollows;

    #[ORM\Column(type: 'boolean')]
    private $approuved;

    public function __construct()
    {
        $this->annoncesFollows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
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

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(?string $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodepostal(): ?int
    {
        return $this->codepostal;
    }

    public function setCodepostal(int $codepostal): self
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, AnnoncesFollow>
     */
    public function getAnnoncesFollows(): Collection
    {
        return $this->annoncesFollows;
    }

    public function addAnnoncesFollow(AnnoncesFollow $annoncesFollow): self
    {
        if (!$this->annoncesFollows->contains($annoncesFollow)) {
            $this->annoncesFollows[] = $annoncesFollow;
            $annoncesFollow->setAnnonces($this);
        }

        return $this;
    }

    public function removeAnnoncesFollow(AnnoncesFollow $annoncesFollow): self
    {
        if ($this->annoncesFollows->removeElement($annoncesFollow)) {
            // set the owning side to null (unless already changed)
            if ($annoncesFollow->getAnnonces() === $this) {
                $annoncesFollow->setAnnonces(null);
            }
        }

        return $this;
    }

    public function getApprouved(): ?bool
    {
        return $this->approuved;
    }

    public function setApprouved(bool $approuved): self
    {
        $this->approuved = $approuved;

        return $this;
    }
}
