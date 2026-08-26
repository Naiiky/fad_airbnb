<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
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

    #[Vich\UploadableField(mapping: 'user_avatar', fileNameProperty: 'avatar')]
    private ?File $avatarFile = null;

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

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $profileUpdatedAt = null;

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

    /** @return array<string, mixed> */
    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'roles' => $this->roles,
            'avatar' => $this->avatar,
        ];
    }

    /** @param array<string, mixed> $data */
    public function __unserialize(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->email = (string) ($data['email'] ?? '');
        $this->password = (string) ($data['password'] ?? '');
        $this->firstname = (string) ($data['firstname'] ?? '');
        $this->lastname = (string) ($data['lastname'] ?? '');
        $this->roles = \is_array($data['roles'] ?? null) ? $data['roles'] : [];
        $this->avatar = \is_string($data['avatar'] ?? null) ? $data['avatar'] : null;
        $this->avatarFile = null;
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(?File $avatarFile): self
    {
        $this->avatarFile = $avatarFile;

        if (null !== $avatarFile) {
            $this->profileUpdatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function getAge(?\DateTimeImmutable $referenceDate = null): ?int
    {
        if (null === $this->birthDate) {
            return null;
        }

        $referenceDate ??= new \DateTimeImmutable();

        return $this->birthDate->diff($referenceDate)->y;
    }

    public function isEmailVerified(): bool
    {
        return $this->emailVerified;
    }

    public function getTermAcceptedAt(): ?\DateTimeImmutable
    {
        return $this->termAcceptedAt;
    }

    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    public function getAgeVerificationStatus(): AgeVerificationStatus
    {
        return $this->ageVerificationStatus;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): self
    {
        $this->country = $country;

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
