<?php

namespace App\Tests;

use App\Booking\BookingCapacityChecker;
use App\Entity\AgeVerificationStatus;
use App\Entity\Country;
use App\Entity\Property;
use App\Entity\PropertyCategory;
use App\Entity\PropertyStatus;
use App\Entity\User;
use App\Entity\UserStatus;
use PHPUnit\Framework\TestCase;

class BookingCapacityCheckerTest extends TestCase
{
    public function testExactCapacityIsAccepted(): void
    {
        $property = $this->createProperty();
        $property->setMaxGuest(4);

        self::assertTrue((new BookingCapacityChecker())->fits($property, 2, 2));
    }

    public function testCapacityOverflowIsRejected(): void
    {
        $property = $this->createProperty();
        $property->setMaxGuest(4);

        self::assertFalse((new BookingCapacityChecker())->fits($property, 3, 2));
    }

    public function testInvalidCountsAreRejected(): void
    {
        $property = $this->createProperty();

        self::assertFalse((new BookingCapacityChecker())->fits($property, 0, 0));
        self::assertFalse((new BookingCapacityChecker())->fits($property, 1, -1));
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
