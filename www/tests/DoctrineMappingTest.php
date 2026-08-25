<?php

namespace App\Tests;

use App\Entity\AgeVerificationStatus;
use App\Entity\Booking;
use App\Entity\BookingStatus;
use App\Entity\Conversation;
use App\Entity\Country;
use App\Entity\Equipment;
use App\Entity\FavoriteProperty;
use App\Entity\Language;
use App\Entity\Message;
use App\Entity\Price;
use App\Entity\Property;
use App\Entity\PropertyCategory;
use App\Entity\PropertyEquipment;
use App\Entity\PropertyImage;
use App\Entity\PropertyStatus;
use App\Entity\Review;
use App\Entity\User;
use App\Entity\UserLanguage;
use App\Entity\UserStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DoctrineMappingTest extends KernelTestCase
{
    public function testMpdEntitiesAreMapped(): void
    {
        self::bootKernel();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $expectedEntities = [
            User::class,
            Language::class,
            UserLanguage::class,
            UserStatus::class,
            AgeVerificationStatus::class,
            Country::class,
            Property::class,
            PropertyCategory::class,
            PropertyStatus::class,
            Equipment::class,
            PropertyEquipment::class,
            PropertyImage::class,
            Booking::class,
            BookingStatus::class,
            Price::class,
            FavoriteProperty::class,
            Review::class,
            Conversation::class,
            Message::class,
        ];

        $metadata = array_map(static fn (object $classMetadata): string => $classMetadata->name, $entityManager->getMetadataFactory()->getAllMetadata());
        sort($expectedEntities);
        sort($metadata);

        self::assertSame($expectedEntities, $metadata);
    }

    public function testCompositeAssociationPrimaryKeysAreMapped(): void
    {
        self::bootKernel();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        self::assertSame(['user', 'language'], $entityManager->getClassMetadata(UserLanguage::class)->getIdentifierFieldNames());
        self::assertSame(['property', 'equipment'], $entityManager->getClassMetadata(PropertyEquipment::class)->getIdentifierFieldNames());
        self::assertSame(['user', 'property'], $entityManager->getClassMetadata(FavoriteProperty::class)->getIdentifierFieldNames());
    }

    public function testUserLanguageCanBeAddedAndRemovedWithoutDuplicates(): void
    {
        $user = $this->createUser();
        $language = new Language('FRENCH');

        $user->addLanguage($language);
        $user->addLanguage($language);

        self::assertCount(1, $user->getUserLanguages());

        $user->removeLanguage($language);

        self::assertCount(0, $user->getUserLanguages());
    }

    public function testPropertyEquipmentCanBeAddedAndRemovedWithoutDeletingEquipment(): void
    {
        $property = $this->createProperty();
        $equipment = new Equipment('Wi-Fi');

        $property->addEquipment($equipment);
        $property->addEquipment($equipment);

        self::assertCount(1, $property->getPropertyEquipments());

        $property->removeEquipment($equipment);

        self::assertCount(0, $property->getPropertyEquipments());
        self::assertSame('Wi-Fi', $equipment->getLabel());
    }

    public function testBookingValidationRejectsInvalidDatesAndCounts(): void
    {
        self::bootKernel();
        $validator = static::getContainer()->get(ValidatorInterface::class);

        $booking = new Booking(
            new BookingStatus('PENDING'),
            $this->createProperty(),
            $this->createUser('traveler@example.com'),
            new \DateTimeImmutable('2026-09-10'),
            new \DateTimeImmutable('2026-09-09'),
            0,
            -1,
            -1,
            -1,
            -1,
        );

        self::assertGreaterThanOrEqual(6, $validator->validate($booking)->count());
    }

    private function createUser(string $email = 'host@example.com'): User
    {
        return new User(
            $email,
            'hashed-password',
            'Alice',
            'Martin',
            new UserStatus('ACTIVE'),
            new AgeVerificationStatus('VERIFIED'),
            new Country('France'),
        );
    }

    private function createProperty(): Property
    {
        return new Property(
            $this->createUser(),
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
