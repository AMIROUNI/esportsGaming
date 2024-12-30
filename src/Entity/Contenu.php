<?php
namespace App\Entity;

use App\Repository\ContenuRepository;
use App\Entity\BlogCategory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContenuRepository::class)]
class Contenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $data =null; // New attribute

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToMany(targetEntity: BlogCategory::class, inversedBy: 'contenus')]
    private Collection $categories;

    /**
     * @var Collection<int, BlogCategory>
     */
    #[ORM\ManyToMany(targetEntity: BlogCategory::class, mappedBy: 'contenus')]
    private Collection $blogCategories;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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

    public function getImage(): ?string
    {
        return $this->image ? 'upload/images/contenu/' . $this->image : null;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): static
    {
        $this->data = $data;

        return $this;
    }




    public function __construct()
{
    $this->categories = new ArrayCollection();
    $this->blogCategories = new ArrayCollection();
}

public function getCategories(): Collection
{
    return $this->categories;
}

public function addCategory(BlogCategory $category): self
{
    if (!$this->categories->contains($category)) {
        $this->categories->add($category);
    }
    return $this;
}

public function removeCategory(BlogCategory $category): self
{
    $this->categories->removeElement($category);
    return $this;
}

/**
 * @return Collection<int, BlogCategory>
 */
public function getBlogCategories(): Collection
{
    return $this->blogCategories;
}

public function addBlogCategory(BlogCategory $blogCategory): static
{
    if (!$this->blogCategories->contains($blogCategory)) {
        $this->blogCategories->add($blogCategory);
        $blogCategory->addContenu($this);
    }

    return $this;
}

public function removeBlogCategory(BlogCategory $blogCategory): static
{
    if ($this->blogCategories->removeElement($blogCategory)) {
        $blogCategory->removeContenu($this);
    }

    return $this;
}



public function getContent(): ?string
{
    return $this->content;
}

public function setContent(?string $content): self
{
    $this->content = $content;

    return $this;
}
}
