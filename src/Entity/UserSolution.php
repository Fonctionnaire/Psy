<?php

namespace App\Entity;

use App\Repository\UserSolutionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserSolutionRepository::class)]
class UserSolution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Veuillez choisir une réponse.')]
    private ?string $currentState = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Veuillez choisir une réponse.')]
    private ?bool $isDisabling = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Veuillez choisir une réponse.')]
    private ?string $howLong = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Veuillez choisir une réponse.')]
    private ?bool $isSource = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Veuillez choisir une réponse.')]
    private ?bool $isDoctorConsulted = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Veuillez choisir une réponse.')]
    private ?bool $isPsyConsulted = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPsyAfraid = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Veuillez choisir une catégorie.')]
    private ?string $category = null;

    #[ORM\OneToOne(inversedBy: 'userSolution', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $isExcitingProduct = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $customAdvices = [];

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
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

    public function getCurrentState(): ?string
    {
        return $this->currentState;
    }

    public function setCurrentState(string $currentState): static
    {
        $this->currentState = $currentState;

        return $this;
    }

    public function isIsDisabling(): ?bool
    {
        return $this->isDisabling;
    }

    public function setIsDisabling(bool $isDisabling): static
    {
        $this->isDisabling = $isDisabling;

        return $this;
    }

    public function getHowLong(): ?string
    {
        return $this->howLong;
    }

    public function setHowLong(string $howLong): static
    {
        $this->howLong = $howLong;

        return $this;
    }

    public function isIsSource(): ?bool
    {
        return $this->isSource;
    }

    public function setIsSource(bool $isSource): static
    {
        $this->isSource = $isSource;

        return $this;
    }

    public function isIsDoctorConsulted(): ?bool
    {
        return $this->isDoctorConsulted;
    }

    public function setIsDoctorConsulted(bool $isDoctorConsulted): static
    {
        $this->isDoctorConsulted = $isDoctorConsulted;

        return $this;
    }

    public function isIsPsyConsulted(): ?bool
    {
        return $this->isPsyConsulted;
    }

    public function setIsPsyConsulted(bool $isPsyConsulted): static
    {
        $this->isPsyConsulted = $isPsyConsulted;

        return $this;
    }

    public function isIsPsyAfraid(): ?bool
    {
        return $this->isPsyAfraid;
    }

    public function setIsPsyAfraid(bool $isPsyAfraid): static
    {
        $this->isPsyAfraid = $isPsyAfraid;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

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

    public function isIsExcitingProduct(): ?bool
    {
        return $this->isExcitingProduct;
    }

    public function setIsExcitingProduct(bool $isExcitingProduct): static
    {
        $this->isExcitingProduct = $isExcitingProduct;

        return $this;
    }

    public function getCustomAdvices(): array
    {
        return $this->customAdvices;
    }

    public function setCustomAdvices(array $customAdvices): static
    {
        $this->customAdvices = $customAdvices;

        return $this;
    }
}
