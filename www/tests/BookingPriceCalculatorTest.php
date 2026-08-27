<?php

namespace App\Tests;

use App\Booking\BookingPriceCalculator;
use App\Entity\AgeVerificationStatus;
use App\Entity\Country;
use App\Entity\Property;
use App\Entity\PropertyCategory;
use App\Entity\PropertyStatus;
use App\Entity\User;
use App\Entity\UserStatus;
use PHPUnit\Framework\TestCase;

class BookingPriceCalculatorTest extends TestCase
{
    public function testPriceSnapshotUsesNightlyPriceCleaningFeeAndDeposit(): void
    {
        $property = $this->createProperty();
        $property->updateFees(250, 35, 120, 180);

        $snapshot = (new BookingPriceCalculator())->calculate(
            $property,
            new \DateTimeImmutable('2026-09-10'),
            new \DateTimeImmutable('2026-09-13'),
        );

        self::assertSame(3, $snapshot->nights);
        self::assertSame(360, $snapshot->nightSubtotal);
        self::assertSame(35, $snapshot->cleaningFee);
        self::assertSame(250, $snapshot->deposit);
        self::assertSame(645, $snapshot->totalAmount);
    }

    public function testInvalidDateRangeIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new BookingPriceCalculator())->calculate(
            $this->createProperty(),
            new \DateTimeImmutable('2026-09-10'),
            new \DateTimeImmutable('2026-09-10'),
        );
    }

    private function createProperty(): Property
    {
        return new Property(
            new User(
                'host@example.com',
                'hashed-password',
                'Alice',
                'Martin',
                new UserStatus('ACTIVE'),
                new AgeVerificationStatus('VERIFIED'),
                new Country('France'),
            ),
            new Country('France'),
            new PropertyCategory('Appartement'),
            new PropertyStatus('PUBLISHED'),
            'Loft',
            'Description',
            '12 rue de Paris',
            'Lyon',
            '69002',
            120,
        );
    }
}
