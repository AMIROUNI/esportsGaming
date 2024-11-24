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

    /**
     * @var Collection<int, DemandeDeProgrammeC>
     */
    #[ORM\OneToMany(targetEntity: DemandeDeProgrammeC::class, mappedBy: 'gamer')]
    private Collection $demandeDeProgrammeC;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'gamer')]
    private Collection $commande;

    /**
     * @var Collection<int, ParticipationTournoi>
     */
    #[ORM\OneToMany(targetEntity: ParticipationTournoi::class, mappedBy: 'gamer')]
    private Collection $participationTournoi;

    /**
     * @var Collection<int, Group>
     */
    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'gamer')]
    private ?Collection $groups =  null;
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

    public function setSurNom(?string $surNom): static
    {
        $this->surNom = $surNom;
        return $this;
    }

    public function getBadge(): ?string
    {
        return $this->badge;
    }

    public function setBadge(?string $badge): static
    {
        $this->badge = $badge;
        return $this;
    }

    /**
     * @return Collection<int, DemandeDeProgrammeC>
     */
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
            // set the owning side to null (unless already changed)
            if ($demandeDeProgrammeC->getGamer() === $this) {
                $demandeDeProgrammeC->setGamer(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commande->contains($commande)) {
            $this->commande->add($commande);
            $commande->setGamer($this);
        }
        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commande->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getGamer() === $this) {
                $commande->setGamer(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, ParticipationTournoi>
     */
    public function getParticipationTournoi(): Collection
    {
        return $this->participationTournoi;
    }

    public function addParticipationTournoi(ParticipationTournoi $participationTournoi): static
    {
        if (!$this->participationTournoi->contains($participationTournoi)) {
            $this->participationTournoi->add($participationTournoi);
            $participationTournoi->setGamer($this);
        }
        return $this;
    }

    public function removeParticipationTournoi(ParticipationTournoi $participationTournoi): static
    {
        if ($this->participationTournoi->removeElement($participationTournoi)) {
            // set the owning side to null (unless already changed)
            if ($participationTournoi->getGamer() === $this) {
                $participationTournoi->setGamer(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): static
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->addGamer($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): static
    {
        if ($this->groups->removeElement($group)) {
            $group->removeGamer($this);
        }

        return $this;
    }
}
