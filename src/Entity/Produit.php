<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomProduits = null;

    #[ORM\Column]
    private ?float $prix = null;

    
    


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: 'float')]
    private float $rating;


    /**
     * @var Collection<int, Lp>
     */
    #[ORM\OneToMany(targetEntity: Lp::class, mappedBy: 'produit')]
    private Collection $lp;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?ProduitCategory $produitCategory = null;

    public function __construct()
    {
        $this->lp = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getImage(): ?string
    {
        return $this->image ? 'upload/images/produits/' . $this->image : null;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getNomProduits(): ?string
    {
        return $this->nomProduits;
    }

    public function setNomProduits(string $nomProduits): static
    {
        $this->nomProduits = $nomProduits;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

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
            $lp->setProduit($this);
        }

        return $this;
    }

    public function removeLp(Lp $lp): static
    {
        if ($this->lp->removeElement($lp)) {
            // set the owning side to null (unless already changed)
            if ($lp->getProduit() === $this) {
                $lp->setProduit(null);
            }
        }

        return $this;
    }

    public function getProduitCategory(): ?ProduitCategory
    {
        return $this->produitCategory;
    }

    public function setProduitCategory(?ProduitCategory $produitCategory): static
    {
        $this->produitCategory = $produitCategory;

        return $this;
    }




    
}
