<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomGroup = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'groups')]
    private Collection $gamer;  // Replaced Gamer with User

    public function __construct()
    {
        $this->gamer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomGroup(): ?string
    {
        return $this->nomGroup;
    }

    public function setNomGroup(string $nomGroup): static
    {
        $this->nomGroup = $nomGroup;

        return $this;
    }

   
    public function getLogo(): ?string
    {
        return $this->logo ? 'upload/images/group/' . $this->logo : null;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getGamer(): Collection
    {
        return $this->gamer;
    }

    public function addGamer(User $gamer): static  // Updated parameter type to User
    {
        if (!$this->gamer->contains($gamer)) {
            $this->gamer->add($gamer);
        }

        return $this;
    }

    public function removeGamer(User $gamer): static  // Updated parameter type to User
    {
        $this->gamer->removeElement($gamer);

        return $this;
    }
}
