<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournoiRepository::class)]
class Tournoi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomTournoi = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255)]
    private ?string $recompense = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxParticipants = null;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'tournois')]
    private Collection $game;

    /**
     * @var Collection<int, ParticipationTournoi>
     */
    #[ORM\OneToMany(targetEntity: ParticipationTournoi::class, mappedBy: 'tournoi')]
    private Collection $participationTournois;

    public function __construct()
    {
        $this->game = new ArrayCollection();
        $this->participationTournois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTournoi(): ?string
    {
        return $this->nomTournoi;
    }

    public function setNomTournoi(string $nomTournoi): static
    {
        $this->nomTournoi = $nomTournoi;

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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getRecompense(): ?string
    {
        return $this->recompense;
    }

    public function setRecompense(string $recompense): static
    {
        $this->recompense = $recompense;

        return $this;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(?int $maxParticipants): static
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGame(): Collection
    {
        return $this->game;
    }

    public function addGame(Game $game): static
    {
        if (!$this->game->contains($game)) {
            $this->game->add($game);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        $this->game->removeElement($game);

        return $this;
    }

    /**
     * @return Collection<int, ParticipationTournoi>
     */
    public function getParticipationTournois(): Collection
    {
        return $this->participationTournois;
    }

    public function addParticipationTournoi(ParticipationTournoi $participationTournoi): static
    {
        if (!$this->participationTournois->contains($participationTournoi)) {
            $this->participationTournois->add($participationTournoi);
            $participationTournoi->setTournoi($this);
        }

        return $this;
    }

    public function removeParticipationTournoi(ParticipationTournoi $participationTournoi): static
    {
        if ($this->participationTournois->removeElement($participationTournoi)) {
            // set the owning side to null (unless already changed)
            if ($participationTournoi->getTournoi() === $this) {
                $participationTournoi->setTournoi(null);
            }
        }

        return $this;
    }
}
