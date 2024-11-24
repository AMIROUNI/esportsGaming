<?php

namespace App\Entity;

use App\Repository\LpRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LpRepository::class)]
class Lp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $qteProduit = null;

    #[ORM\ManyToOne(inversedBy: 'lp')]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(inversedBy: 'lp')]
    private ?Produit $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQteProduit(): ?int
    {
        return $this->qteProduit;
    }

    public function setQteProduit(?int $qteProduit): static
    {
        $this->qteProduit = $qteProduit;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}
