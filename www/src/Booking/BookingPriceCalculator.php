<?php

namespace App\Booking;

use App\Entity\Property;

class BookingPriceCalculator
{
    public function calculate(Property $property, \DateTimeImmutable $checkIn, \DateTimeImmutable $checkOut): BookingPriceSnapshot
    {
        $nights = (int) $checkIn->diff($checkOut)->days;
        if ($nights <= 0) {
            throw new \InvalidArgumentException('La date de depart doit etre posterieure a la date d arrivee.');
        }

        $nightSubtotal = $nights * $property->getNightlyPrice();
        $cleaningFee = $property->getCleaningFee();
        $deposit = $property->getDeposit();

        return new BookingPriceSnapshot(
            $nights,
            $nightSubtotal,
            $cleaningFee,
            $deposit,
            $nightSubtotal + $cleaningFee + $deposit,
        );
    }
}
