<?php

namespace App\Booking;

use App\Entity\Property;

class BookingCapacityChecker
{
    public function fits(Property $property, int $adultCount, int $childrenCount): bool
    {
        return $adultCount >= 1
            && $childrenCount >= 0
            && ($adultCount + $childrenCount) <= $property->getMaxGuest();
    }
}
