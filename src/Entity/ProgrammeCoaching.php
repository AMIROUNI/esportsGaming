<?php

namespace App\Entity;

use App\Repository\ProgrammeCoachingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\DemandeDeProgrammeC;

#[ORM\Entity(repositoryClass: ProgrammeCoachingRepository::class)]
class ProgrammeCoaching
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(nullable: true)]
    private ?int $duree = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, DemandeDeProgrammeC>
     */
    #[ORM\OneToMany(targetEntity: DemandeDeProgrammeC::class, mappedBy: 'programmeCoaching', orphanRemoval: true)]
    private Collection $demandeDeProgrammeC;

    public function __construct()
    {
        $this->demandeDeProgrammeC = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, DemandeDeProgrammeC>
     */
    public function getDemandeDeProgrammeC(): Collection
    {
        return $this->demandeDeProgrammeC;
    }

    public function addDemandeDeProgrammeC(DemandeDeProgrammeC $demande): static
    {
        if (!$this->demandeDeProgrammeC->contains($demande)) {
            $this->demandeDeProgrammeC->add($demande);
            $demande->setProgrammeCoaching($this);
        }

        return $this;
    }

    public function removeDemandeDeProgrammeC(DemandeDeProgrammeC $demande): static
    {
        if ($this->demandeDeProgrammeC->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getProgrammeCoaching() === $this) {
                $demande->setProgrammeCoaching(null);
            }
        }

        return $this;
    }
}
