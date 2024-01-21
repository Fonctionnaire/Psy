<?php

namespace App\Entity;

use App\Repository\ForumCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForumCategoryRepository::class)]
class ForumCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'forumCategory', targetEntity: ForumSubject::class)]
    private Collection $forumSubjects;

    public function __construct()
    {
        $this->forumSubjects = new ArrayCollection();
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
     * @return Collection<int, ForumSubject>
     */
    public function getForumSubjects(): Collection
    {
        return $this->forumSubjects;
    }

    public function addForumSubject(ForumSubject $forumSubject): static
    {
        if (!$this->forumSubjects->contains($forumSubject)) {
            $this->forumSubjects->add($forumSubject);
            $forumSubject->setForumCategory($this);
        }

        return $this;
    }

    public function removeForumSubject(ForumSubject $forumSubject): static
    {
        if ($this->forumSubjects->removeElement($forumSubject)) {
            // set the owning side to null (unless already changed)
            if ($forumSubject->getForumCategory() === $this) {
                $forumSubject->setForumCategory(null);
            }
        }

        return $this;
    }
}
