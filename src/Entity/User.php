<?php
// src/Entity/User.php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 100, nullable: true)] 
       private ?string $nom = null;

#[ORM\Column(length: 100, nullable: true)] 
       private ?string $prenom = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $niveau = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $surNom = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $badge = null;

    // Relation Many-to-Many avec Group
    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'gamer')]
    private Collection $groups;



    #[ORM\OneToOne(targetEntity: Card::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
private ?Card $card = null;

public function getCard(): ?Card
{
    return $this->card;
}

public function setCard(?Card $card): static
{
    // Set the owning side of the relation if necessary
    if ($card->getUser() !== $this) {
        $card->setUser($this);
    }

    $this->card = $card;

    return $this;
}

    #[ORM\Column]
    private bool $isVerified = false;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->nom = 'Default Nom';
        $this->prenom = 'Default Prenom';
        $this->roles = ['ROLE_USER'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Clear sensitive data
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
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

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(?int $niveau): static
    {
        $this->niveau = $niveau;
        return $this;
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
        }
        return $this;
    }

    public function removeGroup(Group $group): static
    {
        $this->groups->removeElement($group);
        return $this;
    }



    public function __toString(): string
    {
        return $this->email; // Or any other field you want to display in the dropdown
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }




}
