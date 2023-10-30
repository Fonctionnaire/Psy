<?php

namespace App\Entity;

use App\Repository\TestimonyCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestimonyCategoryRepository::class)]
class TestimonyCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'testimonyCategory', targetEntity: Testimony::class)]
    private Collection $testimony;

    public function __construct()
    {
        $this->testimony = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Testimony>
     */
    public function getTestimony(): Collection
    {
        return $this->testimony;
    }

    public function addTestimony(Testimony $testimony): static
    {
        if (!$this->testimony->contains($testimony)) {
            $this->testimony->add($testimony);
            $testimony->setTestimonyCategory($this);
        }

        return $this;
    }

    public function removeTestimony(Testimony $testimony): static
    {
        if ($this->testimony->removeElement($testimony)) {
            // set the owning side to null (unless already changed)
            if ($testimony->getTestimonyCategory() === $this) {
                $testimony->setTestimonyCategory(null);
            }
        }

        return $this;
    }
}
