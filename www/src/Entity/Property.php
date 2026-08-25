<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\PropertyRepository::class)]
#[ORM\Index(name: 'idx_property_status_city', columns: ['status_id', 'city'])]
#[ORM\Index(name: 'idx_property_user_status', columns: ['user_id', 'status_id'])]
class Property
{
    use IdTrait;

    #[ORM\Column]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column]
    private int $maxGuest;

    #[ORM\Column]
    private int $bedrooms;

    #[ORM\Column]
    private int $bathrooms;

    #[ORM\Column]
    private int $beds;

    #[ORM\Column]
    private int $areaM2;

    #[ORM\Column]
    private string $address;

    #[ORM\Column]
    private string $city;

    #[ORM\Column(length: 20)]
    private string $zipCode;

    #[ORM\Column]
    private int $deposit;

    #[ORM\Column]
    private int $cleaningFee;

    #[ORM\Column]
    private int $reviewCount = 0;

    #[ORM\Column]
    private float $averageRating = 0.0;

    #[ORM\Column]
    private int $nightlyPrice;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column]
    private int $weekendPrice;

    #[ORM\Column]
    private bool $petsAllowed = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private User $user;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private Country $country;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private PropertyCategory $category;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private PropertyStatus $status;

    /** @var Collection<int, PropertyImage> */
    #[ORM\OneToMany(mappedBy: 'property', targetEntity: PropertyImage::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $images;

    /** @var Collection<int, PropertyEquipment> */
    #[ORM\OneToMany(mappedBy: 'property', targetEntity: PropertyEquipment::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $propertyEquipments;

    /** @var Collection<int, Booking> */
    #[ORM\OneToMany(mappedBy: 'property', targetEntity: Booking::class)]
    private Collection $bookings;

    /** @var Collection<int, Price> */
    #[ORM\OneToMany(mappedBy: 'property', targetEntity: Price::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $prices;

    /** @var Collection<int, FavoriteProperty> */
    #[ORM\OneToMany(mappedBy: 'property', targetEntity: FavoriteProperty::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $favoriteProperties;

    /** @var Collection<int, Conversation> */
    #[ORM\OneToMany(mappedBy: 'property', targetEntity: Conversation::class)]
    private Collection $conversations;

    public function __construct(
        User $user,
        Country $country,
        PropertyCategory $category,
        PropertyStatus $status,
        string $title,
        string $description,
        string $address,
        string $city,
        string $zipCode,
        int $nightlyPrice,
    ) {
        $this->initializeId();
        $this->user = $user;
        $this->country = $country;
        $this->category = $category;
        $this->status = $status;
        $this->title = $title;
        $this->description = $description;
        $this->address = $address;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->nightlyPrice = $nightlyPrice;
        $this->maxGuest = 1;
        $this->bedrooms = 1;
        $this->bathrooms = 1;
        $this->beds = 1;
        $this->areaM2 = 1;
        $this->deposit = 0;
        $this->cleaningFee = 0;
        $this->weekendPrice = $nightlyPrice;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
        $this->images = new ArrayCollection();
        $this->propertyEquipments = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->prices = new ArrayCollection();
        $this->favoriteProperties = new ArrayCollection();
        $this->conversations = new ArrayCollection();
    }

    public function updateCapacity(int $maxGuest, int $bedrooms, int $bathrooms, int $beds, int $areaM2): self
    {
        $this->maxGuest = $maxGuest;
        $this->bedrooms = $bedrooms;
        $this->bathrooms = $bathrooms;
        $this->beds = $beds;
        $this->areaM2 = $areaM2;
        $this->touch();

        return $this;
    }

    public function updateFees(int $deposit, int $cleaningFee, int $nightlyPrice, int $weekendPrice): self
    {
        $this->deposit = $deposit;
        $this->cleaningFee = $cleaningFee;
        $this->nightlyPrice = $nightlyPrice;
        $this->weekendPrice = $weekendPrice;
        $this->touch();

        return $this;
    }

    public function setPetsAllowed(bool $petsAllowed): self
    {
        $this->petsAllowed = $petsAllowed;
        $this->touch();

        return $this;
    }

    public function publish(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;
        $this->touch();

        return $this;
    }

    public function delete(\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        $this->touch();

        return $this;
    }

    public function setReviewSummary(int $reviewCount, float $averageRating): self
    {
        $this->reviewCount = $reviewCount;
        $this->averageRating = $averageRating;
        $this->touch();

        return $this;
    }

    public function addEquipment(Equipment $equipment): self
    {
        foreach ($this->propertyEquipments as $propertyEquipment) {
            if ($propertyEquipment->getEquipment() === $equipment) {
                return $this;
            }
        }

        $this->propertyEquipments->add(new PropertyEquipment($this, $equipment));

        return $this;
    }

    public function removeEquipment(Equipment $equipment): self
    {
        foreach ($this->propertyEquipments as $propertyEquipment) {
            if ($propertyEquipment->getEquipment() === $equipment) {
                $this->propertyEquipments->removeElement($propertyEquipment);
                break;
            }
        }

        return $this;
    }

    /** @return Collection<int, PropertyEquipment> */
    public function getPropertyEquipments(): Collection
    {
        return $this->propertyEquipments;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
