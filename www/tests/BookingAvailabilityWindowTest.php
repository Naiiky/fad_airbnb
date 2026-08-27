<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class BookingAvailabilityWindowTest extends TestCase
{
    public function testAdjacentRangesDoNotOverlap(): void
    {
        self::assertFalse($this->overlaps('2026-09-10', '2026-09-15', '2026-09-15', '2026-09-18'));
        self::assertFalse($this->overlaps('2026-09-10', '2026-09-15', '2026-09-05', '2026-09-10'));
    }

    public function testPartialOverlapIsDetected(): void
    {
        self::assertTrue($this->overlaps('2026-09-10', '2026-09-15', '2026-09-14', '2026-09-18'));
        self::assertTrue($this->overlaps('2026-09-10', '2026-09-15', '2026-09-05', '2026-09-11'));
    }

    public function testEqualRangeIsDetected(): void
    {
        self::assertTrue($this->overlaps('2026-09-10', '2026-09-15', '2026-09-10', '2026-09-15'));
    }

    private function overlaps(string $existingStart, string $existingEnd, string $requestedStart, string $requestedEnd): bool
    {
        return new \DateTimeImmutable($existingStart) < new \DateTimeImmutable($requestedEnd)
            && new \DateTimeImmutable($existingEnd) > new \DateTimeImmutable($requestedStart);
    }
}
