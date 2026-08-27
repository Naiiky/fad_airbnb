<?php

namespace App\Booking;

class BookingPriceSnapshot
{
    public function __construct(
        public readonly int $nights,
        public readonly int $nightSubtotal,
        public readonly int $cleaningFee,
        public readonly int $deposit,
        public readonly int $totalAmount,
    ) {
    }
}
