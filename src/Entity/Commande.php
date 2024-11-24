<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDeCommande = null;

    #[ORM\ManyToOne(inversedBy: 'Commande')]
    private ?Gamer $gamer = null;

    /**
     * @var Collection<int, Lp>
     */
    #[ORM\OneToMany(targetEntity: Lp::class, mappedBy: 'commande')]
    private Collection $lp;

    public function __construct()
    {
        $this->lp = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDeCommande(): ?\DateTimeInterface
    {
        return $this->dateDeCommande;
    }

    public function setDateDeCommande(\DateTimeInterface $dateDeCommande): static
    {
        $this->dateDeCommande = $dateDeCommande;

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

    /**
     * @return Collection<int, Lp>
     */
    public function getLp(): Collection
    {
        return $this->lp;
    }

    public function addLp(Lp $lp): static
    {
        if (!$this->lp->contains($lp)) {
            $this->lp->add($lp);
            $lp->setCommande($this);
        }

        return $this;
    }

    public function removeLp(Lp $lp): static
    {
        if ($this->lp->removeElement($lp)) {
            // set the owning side to null (unless already changed)
            if ($lp->getCommande() === $this) {
                $lp->setCommande(null);
            }
        }

        return $this;
    }
}
