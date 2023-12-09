<?php

namespace App\Entity;

use App\Repository\ForumSubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ForumSubjectRepository::class)]
class ForumSubject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez écrire votre message')]
    #[Assert\NotNull(message: 'Veuillez écrire votre message')]
    #[Assert\Length(
        min: 10,
        minMessage: 'Le titre doit comporter au moins {{ limit }} caractères',
        max: 250,
        maxMessage: 'Le titre ne peut comporter plus de {{ limit }} caractères'
    )]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Veuillez écrire votre message')]
    #[Assert\NotNull(message: 'Veuillez écrire votre message')]
    #[Assert\Length(
        min: 15,
        minMessage: 'Votre message doit comporter au moins {{ limit }} caractères',
    )]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'forumSubjects')]
    private ?User $author = null;

    #[ORM\Column]
    private ?bool $isRule = false;

    #[ORM\Column]
    private ?int $nbOfView = 0;

    #[ORM\OneToMany(mappedBy: 'forumSubject', targetEntity: ForumAnswer::class, orphanRemoval: true)]
    private Collection $forumAnswers;

    #[ORM\Column]
    private ?bool $isReported = false;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'forumSubjects')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Veuillez choisir une catégorie')]
    private ?ForumCategory $forumCategory = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastReply = null;

    #[ORM\Column]
    private ?bool $isBan = false;

    public function __construct()
    {
        $this->forumAnswers = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function isIsRule(): ?bool
    {
        return $this->isRule;
    }

    public function setIsRule(bool $isRule): static
    {
        $this->isRule = $isRule;

        return $this;
    }

    public function getNbOfView(): ?int
    {
        return $this->nbOfView;
    }

    public function setNbOfView(int $nbOfView): static
    {
        $this->nbOfView = $nbOfView;

        return $this;
    }

    /**
     * @return Collection<int, ForumAnswer>
     */
    public function getForumAnswers(): Collection
    {
        return $this->forumAnswers;
    }

    public function addForumAnswer(ForumAnswer $forumAnswer): static
    {
        if (!$this->forumAnswers->contains($forumAnswer)) {
            $this->forumAnswers->add($forumAnswer);
            $forumAnswer->setForumSubject($this);
        }

        return $this;
    }

    public function removeForumAnswer(ForumAnswer $forumAnswer): static
    {
        if ($this->forumAnswers->removeElement($forumAnswer)) {
            // set the owning side to null (unless already changed)
            if ($forumAnswer->getForumSubject() === $this) {
                $forumAnswer->setForumSubject(null);
            }
        }

        return $this;
    }

    public function isIsReported(): ?bool
    {
        return $this->isReported;
    }

    public function setIsReported(bool $isReported): static
    {
        $this->isReported = $isReported;

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

    public function getForumCategory(): ?ForumCategory
    {
        return $this->forumCategory;
    }

    public function setForumCategory(?ForumCategory $forumCategory): static
    {
        $this->forumCategory = $forumCategory;

        return $this;
    }

    public function getLastReply(): ?\DateTimeImmutable
    {
        return $this->lastReply;
    }

    public function setLastReply(?\DateTimeImmutable $lastReply): static
    {
        $this->lastReply = $lastReply;

        return $this;
    }

    public function isIsBan(): ?bool
    {
        return $this->isBan;
    }

    public function setIsBan(bool $isBan): static
    {
        $this->isBan = $isBan;

        return $this;
    }
}
