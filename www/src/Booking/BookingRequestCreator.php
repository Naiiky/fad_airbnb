<?php

namespace App\Booking;

use App\Entity\Booking;
use App\Entity\BookingStatus;
use App\Entity\Property;
use App\Entity\User;
use App\Repository\BookingRepository;
use App\Repository\BookingStatusRepository;

class BookingRequestCreator
{
    public function __construct(
        private readonly BookingStatusRepository $statusRepository,
        private readonly BookingRepository $bookingRepository,
        private readonly BookingPriceCalculator $priceCalculator,
        private readonly BookingCapacityChecker $capacityChecker,
    ) {
    }

    public function create(
        Property $property,
        User $user,
        \DateTimeImmutable $checkIn,
        \DateTimeImmutable $checkOut,
        int $adultCount,
        int $childrenCount,
    ): Booking {
        if ($property->getUser()->getId() === $user->getId()) {
            throw new \InvalidArgumentException('Vous ne pouvez pas reserver votre propre logement.');
        }

        if ($checkOut <= $checkIn) {
            throw new \InvalidArgumentException('La date de depart doit etre posterieure a la date d arrivee.');
        }

        if (!$this->capacityChecker->fits($property, $adultCount, $childrenCount)) {
            throw new \InvalidArgumentException('Le nombre de voyageurs depasse la capacite du logement.');
        }

        if (!$this->bookingRepository->isAvailable($property, $checkIn, $checkOut)) {
            throw new \InvalidArgumentException('Ce logement n est pas disponible sur ces dates.');
        }

        $snapshot = $this->priceCalculator->calculate($property, $checkIn, $checkOut);

        return new Booking(
            $this->getStatus('PENDING'),
            $property,
            $user,
            $checkIn,
            $checkOut,
            $adultCount,
            $snapshot->nightSubtotal,
            $snapshot->cleaningFee,
            $snapshot->deposit,
            $snapshot->totalAmount,
            $childrenCount,
        );
    }

    private function getStatus(string $label): BookingStatus
    {
        $status = $this->statusRepository->findOneBy(['label' => $label]);
        if (!$status instanceof BookingStatus) {
            throw new \RuntimeException(sprintf('Le statut de reservation "%s" est introuvable.', $label));
        }

        return $status;
    }
}
