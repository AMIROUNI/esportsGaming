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

    #[ORM\ManyToOne(inversedBy: 'commande')]
    private ?Gamer $gamer = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Lp::class)]
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

    public function setDateDeCommande(\DateTimeInterface $dateDeCommande): self
    {
        $this->dateDeCommande = $dateDeCommande;

        return $this;
    }

    public function getGamer(): ?Gamer
    {
        return $this->gamer;
    }

    public function setGamer(?Gamer $gamer): self
    {
        $this->gamer = $gamer;

        return $this;
    }

    // Methods for lp...
}
