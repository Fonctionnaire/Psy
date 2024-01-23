<?php

namespace App\Entity;

use App\Repository\DonationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DonationRepository::class)]
class Donation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner ce champ')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Ce champ ne peut pas contenir plus de {{ limit }} caractères',
    )]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Email(
        message: '{{ value }} n\'est pas un e-mail valide.',
    )]
    #[Assert\NotBlank(message: 'Veuillez renseigner ce champ')]
    private ?string $email;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: 'Veuillez saisir un montant')]
    private float $price;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $stripeSessionId;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'boolean')]
    private bool $isAcceptTerms = false;

    #[ORM\Column(type: 'boolean')]
    private $isPaid = false;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price * 100;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getStripeSessionId(): ?string
    {
        return $this->stripeSessionId;
    }

    public function setStripeSessionId(?string $stripeSessionId): self
    {
        $this->stripeSessionId = $stripeSessionId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsAcceptTerms(): ?bool
    {
        return $this->isAcceptTerms;
    }

    public function setIsAcceptTerms(bool $isAcceptTerms): self
    {
        $this->isAcceptTerms = $isAcceptTerms;

        return $this;
    }

    public function getIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }
}
