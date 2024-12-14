<?php

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
        $this->commande = new ArrayCollection();
        $this->participationTournoi = new ArrayCollection();
        $this->groups = new ArrayCollection();
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

    // Methods for demandeDeProgrammeC, commande, participationTournoi, and groups...
}
