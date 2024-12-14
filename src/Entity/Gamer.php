<?php 
// src/Entity/Gamer.php

namespace App\Entity;

use App\Repository\GamerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GamerRepository::class)]
class Gamer extends User
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $surNom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $badge = null;

    #[ORM\OneToMany(mappedBy: 'gamer', targetEntity: DemandeDeProgrammeC::class)]
    private Collection $demandeDeProgrammeC;


    #[ORM\OneToMany(mappedBy: 'gamer', targetEntity: Commande::class)]
    private Collection $commande;

    #[ORM\OneToMany(mappedBy: 'gamer', targetEntity: ParticipationTournoi::class)]
    private Collection $participationTournoi;

    #[ORM\ManyToMany(mappedBy: 'gamer', targetEntity: Group::class)]
    private Collection $groups;


    public function __construct()
    {
        $this->demandeDeProgrammeC = new ArrayCollection();
    }

    public function getSurNom(): ?string
    {
        return $this->surNom;
    }

    public function setSurNom(?string $surNom): self
    {
        $this->surNom = $surNom;
        return $this;
    }

    public function getBadge(): ?string
    {
        return $this->badge;
    }

    public function setBadge(?string $badge): self
    {
        $this->badge = $badge;
        return $this;
    }


    public function getDemandeDeProgrammeC(): Collection
    {
        return $this->demandeDeProgrammeC;
    }

    public function addDemandeDeProgrammeC(DemandeDeProgrammeC $demandeDeProgrammeC): static
    {
        if (!$this->demandeDeProgrammeC->contains($demandeDeProgrammeC)) {
            $this->demandeDeProgrammeC->add($demandeDeProgrammeC);
            $demandeDeProgrammeC->setGamer($this);
        }
        return $this;
    }

    public function removeDemandeDeProgrammeC(DemandeDeProgrammeC $demandeDeProgrammeC): static
    {
        if ($this->demandeDeProgrammeC->removeElement($demandeDeProgrammeC)) {
            if ($demandeDeProgrammeC->getGamer() === $this) {
                $demandeDeProgrammeC->setGamer(null);
            }
        }
        return $this;
    }

}
