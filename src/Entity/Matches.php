<?php
// src/Entity/Match.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Matches
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Group::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Group $groupA;

    #[ORM\ManyToOne(targetEntity: Group::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Group $groupB;

    #[ORM\ManyToOne(targetEntity: Tournoi::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Tournoi $tournoi;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $matchDate;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $scoreA = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $scoreB = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getGroupA(): Group
    {
        return $this->groupA;
    }

    public function setGroupA(Group $groupA): self
    {
        $this->groupA = $groupA;
        return $this;
    }

    public function getGroupB(): Group
    {
        return $this->groupB;
    }

    public function setGroupB(Group $groupB): self
    {
        $this->groupB = $groupB;
        return $this;
    }

    public function getTournoi(): Tournoi
    {
        return $this->tournoi;
    }

    public function setTournoi(Tournoi $tournoi): self
    {
        $this->tournoi = $tournoi;
        return $this;
    }

    public function getMatchDate(): \DateTimeInterface
    {
        return $this->matchDate;
    }

    public function setMatchDate(\DateTimeInterface $matchDate): self
    {
        $this->matchDate = $matchDate;
        return $this;
    }

    // Getter and Setter for scoreA
    public function getScoreA(): ?int
    {
        return $this->scoreA;
    }

    public function setScoreA(?int $scoreA): self
    {
        $this->scoreA = $scoreA;
        return $this;
    }

    // Getter and Setter for scoreB
    public function getScoreB(): ?int
    {
        return $this->scoreB;
    }

    public function setScoreB(?int $scoreB): self
    {
        $this->scoreB = $scoreB;
        return $this;
    }
}
