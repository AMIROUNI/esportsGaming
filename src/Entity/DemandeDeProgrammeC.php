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
    private ?User $gamer = null;  // Replaced Gamer with User

    #[ORM\ManyToOne(inversedBy: 'demandeDeProgrammeCs')]
    private ?User $coach = null;  // Replaced Coach with User

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

    public function getGamer(): ?User
    {
        return $this->gamer;
    }

    public function setGamer(?User $gamer): self
    {
        $this->gamer = $gamer;
        return $this;
    }

    public function getCoach(): ?User
    {
        return $this->coach;
    }

    public function setCoach(?User $coach): self
    {
        $this->coach = $coach;
        return $this;
    }

    public function getProgrammeCoaching(): ?ProgrammeCoaching
    {
        return $this->programmeCoaching;
    }

    public function setProgrammeCoaching(?ProgrammeCoaching $programmeCoaching): self
    {
        $this->programmeCoaching = $programmeCoaching;
        return $this;
    }
}
