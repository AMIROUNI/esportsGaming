<?php

namespace App\Entity;

use App\Repository\DemandeDeProgrammeCRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\EtatDeParticipation;

#[ORM\Entity(repositoryClass: DemandeDeProgrammeCRepository::class)]
class DemandeDeProgrammeC
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', enumType: EtatDeParticipation::class)]
    private EtatDeParticipation $etat;

    #[ORM\ManyToOne(inversedBy: 'demandeDeProgrammeC')]
    private ?Gamer $gamer = null;

    #[ORM\ManyToOne(inversedBy: 'demandeDeProgrammeCs')]
    private ?Coach $coach = null;

    #[ORM\ManyToOne(inversedBy: 'demandeDeProgrammeC')]
    private ?ProgrammeCoaching $programmeCoaching = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): EtatDeParticipation
    {
        return $this->etat;
    }

    public function setEtat(EtatDeParticipation $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getGamer(): ?Gamer
    {
        return $this->gamer;
    }

    public function setGamer(?Gamer $gamer): static
    {
        $this->gamer = $gamer;
        return $this;
    }

    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    public function setCoach(?Coach $coach): static
    {
        $this->coach = $coach;
        return $this;
    }

    public function getProgrammeCoaching(): ?ProgrammeCoaching
    {
        return $this->programmeCoaching;
    }

    public function setProgrammeCoaching(?ProgrammeCoaching $programmeCoaching): static
    {
        $this->programmeCoaching = $programmeCoaching;
        return $this;
    }
}
