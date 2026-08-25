<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: \App\Repository\BookingRepository::class)]
#[ORM\Index(name: 'idx_booking_property_status_dates', columns: ['property_id', 'status_id', 'check_in', 'check_out'])]
#[ORM\Index(name: 'idx_booking_user_status', columns: ['user_id', 'status_id'])]
#[Assert\Expression('this.getCheckOut() > this.getCheckIn()', message: 'La date de fin doit etre posterieure a la date de debut.')]
class Booking
{
    use IdTrait;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $checkIn;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $checkOut;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(1)]
    private int $adultCount;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(0)]
    private int $childrenCount = 0;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(0)]
    private int $nightSubtotal;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(0)]
    private int $cleaningFee;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(0)]
    private int $deposit;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(0)]
    private int $totalAmount;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $cancellationReason = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $cancellationDate = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private BookingStatus $status;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private Property $property;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private User $user;

    #[ORM\OneToOne(mappedBy: 'booking', targetEntity: Review::class)]
    private ?Review $review = null;

    public function __construct(
        BookingStatus $status,
        Property $property,
        User $user,
        \DateTimeImmutable $checkIn,
        \DateTimeImmutable $checkOut,
        int $adultCount,
        int $nightSubtotal,
        int $cleaningFee,
        int $deposit,
        int $totalAmount,
    ) {
        $this->initializeId();
        $this->status = $status;
        $this->property = $property;
        $this->user = $user;
        $this->checkIn = $checkIn;
        $this->checkOut = $checkOut;
        $this->adultCount = $adultCount;
        $this->nightSubtotal = $nightSubtotal;
        $this->cleaningFee = $cleaningFee;
        $this->deposit = $deposit;
        $this->totalAmount = $totalAmount;
    }

    public function getCheckIn(): \DateTimeImmutable
    {
        return $this->checkIn;
    }

    public function getCheckOut(): \DateTimeImmutable
    {
        return $this->checkOut;
    }

    public function cancel(string $reason, \DateTimeImmutable $date): self
    {
        $this->cancellationReason = $reason;
        $this->cancellationDate = $date;

        return $this;
    }
}
