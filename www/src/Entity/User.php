<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: \App\Repository\UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'uniq_user_email', columns: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use IdTrait;

    #[ORM\Column(length: 150)]
    private string $email;

    #[ORM\Column]
    private string $password;

    #[ORM\Column]
    private string $firstname;

    #[ORM\Column]
    private string $lastname;

    #[ORM\Column(nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(nullable: true)]
    private ?string $address = null;

    #[ORM\Column(nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $zipCode = null;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private ?\DateTimeImmutable $birthDate = null;

    #[ORM\Column]
    private bool $emailVerified = false;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $termAcceptedAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    /** @var list<string> */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private UserStatus $status;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private AgeVerificationStatus $ageVerificationStatus;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private Country $country;

    /** @var Collection<int, Property> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Property::class)]
    private Collection $properties;

    /** @var Collection<int, Booking> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Booking::class)]
    private Collection $bookings;

    /** @var Collection<int, Conversation> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Conversation::class)]
    private Collection $conversations;

    /** @var Collection<int, Message> */
    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Message::class)]
    private Collection $sentMessages;

    /** @var Collection<int, UserLanguage> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserLanguage::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $userLanguages;

    /** @var Collection<int, FavoriteProperty> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FavoriteProperty::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $favoriteProperties;

    public function __construct(
        string $email,
        string $password,
        string $firstname,
        string $lastname,
        UserStatus $status,
        AgeVerificationStatus $ageVerificationStatus,
        Country $country,
    ) {
        $this->initializeId();
        $this->email = $email;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->status = $status;
        $this->ageVerificationStatus = $ageVerificationStatus;
        $this->country = $country;
        $this->properties = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->userLanguages = new ArrayCollection();
        $this->favoriteProperties = new ArrayCollection();
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /** @return list<string> */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_values(array_unique($roles));
    }

    /** @param list<string> $roles */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setProfile(
        string $firstname,
        string $lastname,
        ?string $phone = null,
        ?string $avatar = null,
        ?string $bio = null,
        ?string $address = null,
        ?string $city = null,
        ?string $zipCode = null,
        ?\DateTimeImmutable $birthDate = null,
    ): self {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->phone = $phone;
        $this->avatar = $avatar;
        $this->bio = $bio;
        $this->address = $address;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->birthDate = $birthDate;

        return $this;
    }

    public function acceptTerms(\DateTimeImmutable $acceptedAt): self
    {
        $this->termAcceptedAt = $acceptedAt;

        return $this;
    }

    public function verifyEmail(): self
    {
        $this->emailVerified = true;

        return $this;
    }

    public function delete(\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function addLanguage(Language $language): self
    {
        foreach ($this->userLanguages as $userLanguage) {
            if ($userLanguage->getLanguage() === $language) {
                return $this;
            }
        }

        $this->userLanguages->add(new UserLanguage($this, $language));

        return $this;
    }

    /** @return Collection<int, UserLanguage> */
    public function getUserLanguages(): Collection
    {
        return $this->userLanguages;
    }

    public function removeLanguage(Language $language): self
    {
        foreach ($this->userLanguages as $userLanguage) {
            if ($userLanguage->getLanguage() === $language) {
                $this->userLanguages->removeElement($userLanguage);
                break;
            }
        }

        return $this;
    }
}
