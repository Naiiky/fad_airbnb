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
    #[ORM\OrderBy(['displayOrder' => 'ASC'])]
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

    public function updateDetails(
        string $title,
        string $description,
        string $address,
        string $city,
        string $zipCode,
        Country $country,
        PropertyCategory $category,
    ): self {
        $this->title = $title;
        $this->description = $description;
        $this->address = $address;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->country = $country;
        $this->category = $category;
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

    public function setStatus(PropertyStatus $status): self
    {
        $this->status = $status;
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

    public function addImage(string $image): PropertyImage
    {
        $propertyImage = new PropertyImage($this, $image, $this->images->count(), $this->images->isEmpty());
        $this->images->add($propertyImage);
        $this->touch();

        return $propertyImage;
    }

    public function removeImage(PropertyImage $image): self
    {
        if (!$this->images->contains($image)) {
            return $this;
        }

        $wasMain = $image->isMain();
        $this->images->removeElement($image);
        $this->normalizeImageOrder();

        if ($wasMain && !$this->images->isEmpty()) {
            $this->setMainImage($this->images->first());
        }

        $this->touch();

        return $this;
    }

    public function setMainImage(PropertyImage $mainImage): self
    {
        if (!$this->images->contains($mainImage)) {
            throw new \InvalidArgumentException('Cette image n appartient pas au logement.');
        }

        foreach ($this->images as $image) {
            $image->setMain($image === $mainImage);
        }

        $this->touch();

        return $this;
    }

    /** @param list<string> $orderedImageIds */
    public function reorderImages(array $orderedImageIds): self
    {
        $imagesById = [];
        foreach ($this->images as $image) {
            if (null !== $image->getId()) {
                $imagesById[$image->getId()] = $image;
            }
        }

        $position = 0;
        foreach ($orderedImageIds as $imageId) {
            if (isset($imagesById[$imageId])) {
                $imagesById[$imageId]->setDisplayOrder($position++);
                unset($imagesById[$imageId]);
            }
        }

        foreach ($imagesById as $image) {
            $image->setDisplayOrder($position++);
        }

        $this->touch();

        return $this;
    }

    /** @return Collection<int, PropertyImage> */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getMainImage(): ?PropertyImage
    {
        foreach ($this->images as $image) {
            if ($image->isMain()) {
                return $image;
            }
        }

        return $this->images->first() ?: null;
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        $this->touch();

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        $this->touch();

        return $this;
    }

    public function getMaxGuest(): int
    {
        return $this->maxGuest;
    }

    public function setMaxGuest(int $maxGuest): self
    {
        $this->maxGuest = $maxGuest;
        $this->touch();

        return $this;
    }

    public function getBedrooms(): int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;
        $this->touch();

        return $this;
    }

    public function getBathrooms(): int
    {
        return $this->bathrooms;
    }

    public function setBathrooms(int $bathrooms): self
    {
        $this->bathrooms = $bathrooms;
        $this->touch();

        return $this;
    }

    public function getBeds(): int
    {
        return $this->beds;
    }

    public function setBeds(int $beds): self
    {
        $this->beds = $beds;
        $this->touch();

        return $this;
    }

    public function getAreaM2(): int
    {
        return $this->areaM2;
    }

    public function setAreaM2(int $areaM2): self
    {
        $this->areaM2 = $areaM2;
        $this->touch();

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;
        $this->touch();

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        $this->touch();

        return $this;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;
        $this->touch();

        return $this;
    }

    public function getDeposit(): int
    {
        return $this->deposit;
    }

    public function setDeposit(int $deposit): self
    {
        $this->deposit = $deposit;
        $this->touch();

        return $this;
    }

    public function getCleaningFee(): int
    {
        return $this->cleaningFee;
    }

    public function setCleaningFee(int $cleaningFee): self
    {
        $this->cleaningFee = $cleaningFee;
        $this->touch();

        return $this;
    }

    public function getNightlyPrice(): int
    {
        return $this->nightlyPrice;
    }

    public function setNightlyPrice(int $nightlyPrice): self
    {
        $this->nightlyPrice = $nightlyPrice;
        $this->touch();

        return $this;
    }

    public function getWeekendPrice(): int
    {
        return $this->weekendPrice;
    }

    public function setWeekendPrice(int $weekendPrice): self
    {
        $this->weekendPrice = $weekendPrice;
        $this->touch();

        return $this;
    }

    public function isPetsAllowed(): bool
    {
        return $this->petsAllowed;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): self
    {
        $this->country = $country;
        $this->touch();

        return $this;
    }

    public function getCategory(): PropertyCategory
    {
        return $this->category;
    }

    public function setCategory(PropertyCategory $category): self
    {
        $this->category = $category;
        $this->touch();

        return $this;
    }

    public function getStatus(): PropertyStatus
    {
        return $this->status;
    }

    private function normalizeImageOrder(): void
    {
        $position = 0;
        foreach ($this->images as $image) {
            $image->setDisplayOrder($position++);
        }
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
