<?php

namespace App\Entity;

use App\Repository\CoachRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoachRepository::class)]
class Coach extends User
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $niveau = null;

    /**
     * @var Collection<int, DemandeDeProgrammeC>
     */
    #[ORM\OneToMany(targetEntity: DemandeDeProgrammeC::class, mappedBy: 'Coach')]
    private Collection $demandeDeProgrammeCs;

    public function __construct()
    {
        $this->demandeDeProgrammeCs = new ArrayCollection();
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

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(?string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * @return Collection<int, DemandeDeProgrammeC>
     */
    public function getDemandeDeProgrammeCs(): Collection
    {
        return $this->demandeDeProgrammeCs;
    }

    public function addDemandeDeProgrammeC(DemandeDeProgrammeC $demandeDeProgrammeC): static
    {
        if (!$this->demandeDeProgrammeCs->contains($demandeDeProgrammeC)) {
            $this->demandeDeProgrammeCs->add($demandeDeProgrammeC);
            $demandeDeProgrammeC->setCoach($this);
        }

        return $this;
    }

    public function removeDemandeDeProgrammeC(DemandeDeProgrammeC $demandeDeProgrammeC): static
    {
        if ($this->demandeDeProgrammeCs->removeElement($demandeDeProgrammeC)) {
            // set the owning side to null (unless already changed)
            if ($demandeDeProgrammeC->getCoach() === $this) {
                $demandeDeProgrammeC->setCoach(null);
            }
        }

        return $this;
    }
}
