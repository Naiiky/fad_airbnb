<?php

namespace App\Tests;

use App\Entity\AgeVerificationStatus;
use App\Entity\Country;
use App\Entity\Property;
use App\Entity\PropertyCategory;
use App\Entity\PropertyStatus;
use App\Entity\User;
use App\Entity\UserStatus;
use PHPUnit\Framework\TestCase;

class PropertyImageManagementTest extends TestCase
{
    public function testPropertyKeepsOneMainImageAndStableOrder(): void
    {
        $property = $this->createProperty();

        $first = $property->addImage('first.webp');
        $second = $property->addImage('second.webp');
        $third = $property->addImage('third.webp');

        self::assertTrue($first->isMain());
        self::assertFalse($second->isMain());
        self::assertSame([0, 1, 2], array_map(static fn ($image): int => $image->getDisplayOrder(), $property->getImages()->toArray()));

        $property->setMainImage($third);

        self::assertFalse($first->isMain());
        self::assertTrue($third->isMain());
        self::assertSame($third, $property->getMainImage());

        $property->reorderImages([$third->getId(), $first->getId(), $second->getId()]);

        self::assertSame(1, $first->getDisplayOrder());
        self::assertSame(2, $second->getDisplayOrder());
        self::assertSame(0, $third->getDisplayOrder());
    }

    public function testRemovingMainImagePromotesNextImage(): void
    {
        $property = $this->createProperty();

        $first = $property->addImage('first.webp');
        $second = $property->addImage('second.webp');

        $property->removeImage($first);

        self::assertCount(1, $property->getImages());
        self::assertTrue($second->isMain());
        self::assertSame(0, $second->getDisplayOrder());
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
            new PropertyStatus('DRAFT'),
            'Loft',
            'Description',
            '12 rue de Paris',
            'Lyon',
            '69002',
            120,
        );
    }
}
