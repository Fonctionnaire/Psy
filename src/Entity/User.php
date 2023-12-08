<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
#[UniqueEntity(fields: ['username'], message: 'Il existe déjà un compte avec ce pseudo')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner votre email')]
    #[Assert\Email(message: 'Veuillez renseigner un email valide')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le mot de passe ne peut excéder {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
        message: 'Le mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial (#?!@$%^&*-).'
    )]
    private ?string $password = null;

    private ?string $plainPassword = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner votre pseudo')]
    #[Assert\Length(min: 2, minMessage: 'Votre pseudo doit contenir au moins {{ limit }} caractères', max: 15, maxMessage: 'Votre pseudo doit contenir au maximum {{ limit }} caractères')]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column]
    private ?bool $isAccountValidated = false;

    #[ORM\Column]
    private ?bool $isBan = false;

    #[ORM\Column(type: 'uuid', nullable: true)]
    private ?Uuid $registrationToken;

    #[ORM\Column]
    private ?bool $isAcceptedTerms = false;

    #[ORM\Column(type: 'uuid', nullable: true)]
    private ?Uuid $resetPasswordToken = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserReview $userReview = null;

    #[ORM\Column]
    private bool $isVolunteer = false;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Testimony $testimony = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserConversation $userConversation = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserMessage::class, orphanRemoval: true)]
    private Collection $userMessages;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: ForumSubject::class)]
    private Collection $forumSubjects;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: ForumAnswer::class)]
    private Collection $forumAnswers;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->registrationToken = Uuid::v4();
        $this->userMessages = new ArrayCollection();
        $this->forumSubjects = new ArrayCollection();
        $this->forumAnswers = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function isIsAccountValidated(): ?bool
    {
        return $this->isAccountValidated;
    }

    public function setIsAccountValidated(bool $isAccountValidated): static
    {
        $this->isAccountValidated = $isAccountValidated;

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

    public function getRegistrationToken(): ?string
    {
        return $this->registrationToken;
    }

    public function setRegistrationToken(?string $registrationToken): static
    {
        $this->registrationToken = $registrationToken;

        return $this;
    }

    public function getTest(): ?Uuid
    {
        return $this->test;
    }

    public function setTest(?Uuid $test): static
    {
        $this->test = $test;

        return $this;
    }

    public function isIsAcceptedTerms(): ?bool
    {
        return $this->isAcceptedTerms;
    }

    public function setIsAcceptedTerms(bool $isAcceptedTerms): static
    {
        $this->isAcceptedTerms = $isAcceptedTerms;

        return $this;
    }

    public function getResetPasswordToken(): ?Uuid
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?Uuid $resetPasswordToken): static
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    public function getUserReview(): ?UserReview
    {
        return $this->userReview;
    }

    public function setUserReview(UserReview $userReview): static
    {
        // set the owning side of the relation if necessary
        if ($userReview->getUser() !== $this) {
            $userReview->setUser($this);
        }

        $this->userReview = $userReview;

        return $this;
    }

    public function isIsVolunteer(): ?bool
    {
        return $this->isVolunteer;
    }

    public function setIsVolunteer(bool $isVolunteer): static
    {
        $this->isVolunteer = $isVolunteer;

        return $this;
    }

    public function getTestimony(): ?Testimony
    {
        return $this->testimony;
    }

    public function setTestimony(Testimony $testimony): static
    {
        // set the owning side of the relation if necessary
        if ($testimony->getUser() !== $this) {
            $testimony->setUser($this);
        }

        $this->testimony = $testimony;

        return $this;
    }

    public function getUserConversation(): ?UserConversation
    {
        return $this->userConversation;
    }

    public function setUserConversation(UserConversation $userConversation): static
    {
        // set the owning side of the relation if necessary
        if ($userConversation->getUser() !== $this) {
            $userConversation->setUser($this);
        }

        $this->userConversation = $userConversation;

        return $this;
    }

    /**
     * @return Collection<int, UserMessage>
     */
    public function getUserMessages(): Collection
    {
        return $this->userMessages;
    }

    public function addUserMessage(UserMessage $userMessage): static
    {
        if (!$this->userMessages->contains($userMessage)) {
            $this->userMessages->add($userMessage);
            $userMessage->setUser($this);
        }

        return $this;
    }

    public function removeUserMessage(UserMessage $userMessage): static
    {
        if ($this->userMessages->removeElement($userMessage)) {
            // set the owning side to null (unless already changed)
            if ($userMessage->getUser() === $this) {
                $userMessage->setUser(null);
            }
        }

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
            $forumSubject->setAuthor($this);
        }

        return $this;
    }

    public function removeForumSubject(ForumSubject $forumSubject): static
    {
        if ($this->forumSubjects->removeElement($forumSubject)) {
            // set the owning side to null (unless already changed)
            if ($forumSubject->getAuthor() === $this) {
                $forumSubject->setAuthor(null);
            }
        }

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
            $forumAnswer->setAuthor($this);
        }

        return $this;
    }

    public function removeForumAnswer(ForumAnswer $forumAnswer): static
    {
        if ($this->forumAnswers->removeElement($forumAnswer)) {
            // set the owning side to null (unless already changed)
            if ($forumAnswer->getAuthor() === $this) {
                $forumAnswer->setAuthor(null);
            }
        }

        return $this;
    }
}
