<?php

namespace App\Entity;

use App\Repository\TestimonyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TestimonyRepository::class)]
class Testimony
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Veuillez renseigner votre témoignage')]
    #[Assert\Length(min: 50, minMessage: 'Votre témoignage doit contenir au moins {{ limit }} caractères')]
    private ?string $content = null;

    #[ORM\Column]
    private ?bool $isValidated = false;

    #[ORM\OneToOne(inversedBy: 'testimony', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $token;

    #[ORM\ManyToOne(inversedBy: 'testimony')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TestimonyCategory $testimonyCategory = null;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->token = Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function isIsValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): static
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getToken(): ?Uuid
    {
        return $this->token;
    }

    public function setToken(Uuid $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getTestimonyCategory(): ?TestimonyCategory
    {
        return $this->testimonyCategory;
    }

    public function setTestimonyCategory(?TestimonyCategory $testimonyCategory): static
    {
        $this->testimonyCategory = $testimonyCategory;

        return $this;
    }

}
