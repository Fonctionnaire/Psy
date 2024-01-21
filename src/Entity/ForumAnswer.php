<?php

namespace App\Entity;

use App\Repository\ForumAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ForumAnswerRepository::class)]
class ForumAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Veuillez écrire votre message')]
    #[Assert\NotNull(message: 'Veuillez écrire votre message')]
    #[Assert\Length(
        min: 2,
        minMessage: 'Votre message doit comporter au moins {{ limit }} caractères',
    )]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'forumAnswers')]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'forumAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ForumSubject $forumSubject = null;

    #[ORM\Column]
    private ?bool $isBan = false;

    #[ORM\Column]
    private ?bool $isReported = false;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getForumSubject(): ?ForumSubject
    {
        return $this->forumSubject;
    }

    public function setForumSubject(?ForumSubject $forumSubject): static
    {
        $this->forumSubject = $forumSubject;

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

    public function isIsReported(): ?bool
    {
        return $this->isReported;
    }

    public function setIsReported(bool $isReported): static
    {
        $this->isReported = $isReported;

        return $this;
    }
}
