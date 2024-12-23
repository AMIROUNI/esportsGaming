<?php
// src/Entity/ParticipationTournoi.php

namespace App\Entity;

use App\Enum\EtatDeParticipationTournoi;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ParticipationTournoi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', enumType: EtatDeParticipationTournoi::class)]
    private EtatDeParticipationTournoi $etat;

    #[ORM\ManyToOne(inversedBy: 'participationTournoi')]
    private ?User $gamer = null;  // Replaced Gamer with User

    #[ORM\ManyToOne(inversedBy: 'participationTournois')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tournoi $tournoi = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEtat(): EtatDeParticipationTournoi
    {
        return $this->etat;
    }

    public function setEtat(EtatDeParticipationTournoi $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getGamer(): ?User
    {
        return $this->gamer;  // Return User instead of Gamer
    }

    public function setGamer(?User $gamer): static
    {
        $this->gamer = $gamer;

        return $this;
    }

    public function getTournoi(): ?Tournoi
    {
        return $this->tournoi;
    }

    public function setTournoi(?Tournoi $tournoi): static
    {
        $this->tournoi = $tournoi;

        return $this;
    }
}
