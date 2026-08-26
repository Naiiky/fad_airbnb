<?php

namespace App\DataFixtures;

use App\Entity\AgeVerificationStatus;
use App\Entity\Booking;
use App\Entity\BookingStatus;
use App\Entity\Country;
use App\Entity\Equipment;
use App\Entity\Language;
use App\Entity\Property;
use App\Entity\PropertyCategory;
use App\Entity\PropertyImage;
use App\Entity\PropertyStatus;
use App\Entity\User;
use App\Entity\UserStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $english = new Language('ENGLISH');
        $french = new Language('FRENCH');
        $spanish = new Language('SPANISH');

        $active = new UserStatus('ACTIVE');
        $suspended = new UserStatus('SUSPENDED');
        $deleted = new UserStatus('DELETED');

        $pendingAge = new AgeVerificationStatus('PENDING');
        $verifiedAge = new AgeVerificationStatus('VERIFIED');
        $rejectedAge = new AgeVerificationStatus('REJECTED');

        $france = new Country('France');
        $spain = new Country('Espagne');
        $italy = new Country('Italie');

        $apartment = new PropertyCategory('Appartement');
        $house = new PropertyCategory('Maison');
        $villa = new PropertyCategory('Villa');

        $draft = new PropertyStatus('DRAFT');
        $published = new PropertyStatus('PUBLISHED');
        $hidden = new PropertyStatus('HIDDEN');

        $wifi = new Equipment('Wi-Fi');
        $kitchen = new Equipment('Cuisine equipee');
        $parking = new Equipment('Parking');
        $airConditioning = new Equipment('Climatisation');

        $bookingPending = new BookingStatus('PENDING');
        $bookingAccepted = new BookingStatus('ACCEPTED');
        $bookingRejected = new BookingStatus('REJECTED');
        $bookingCancelled = new BookingStatus('CANCELLED');

        foreach ([
            $english,
            $french,
            $spanish,
            $active,
            $suspended,
            $deleted,
            $pendingAge,
            $verifiedAge,
            $rejectedAge,
            $france,
            $spain,
            $italy,
            $apartment,
            $house,
            $villa,
            $draft,
            $published,
            $hidden,
            $wifi,
            $kitchen,
            $parking,
            $airConditioning,
            $bookingPending,
            $bookingAccepted,
            $bookingRejected,
            $bookingCancelled,
        ] as $reference) {
            $manager->persist($reference);
        }

        $host = $this->createUser('host@example.com', 'Alice', 'Martin', $active, $verifiedAge, $france);
        $traveler = $this->createUser('traveler@example.com', 'Mehdi', 'Bernard', $active, $pendingAge, $france);
        $admin = $this->createUser('admin@example.com', 'Admin', 'Airbnb', $active, $verifiedAge, $france, ['ROLE_ADMIN']);

        foreach ([$host, $traveler, $admin] as $user) {
            $user->acceptTerms(new \DateTimeImmutable('-1 day'));
            $user->addLanguage($french);
            $manager->persist($user);
        }

        $loft = new Property($host, $france, $apartment, $published, 'Loft lumineux centre-ville', 'Logement de demonstration proche des transports.', '12 rue de Paris', 'Lyon', '69002', 120);
        $loft
            ->updateCapacity(4, 2, 1, 2, 65)
            ->updateFees(300, 45, 120, 140)
            ->setPetsAllowed(true)
            ->publish(new \DateTimeImmutable('-2 days'))
            ->addEquipment($wifi)
            ->addEquipment($kitchen);

        $houseProperty = new Property($host, $france, $house, $draft, 'Maison calme avec jardin', 'Maison familiale de demonstration.', '8 avenue des Pins', 'Nantes', '44000', 95);
        $houseProperty
            ->updateCapacity(6, 3, 2, 4, 110)
            ->updateFees(500, 70, 95, 115)
            ->addEquipment($parking);

        foreach ([$loft, $houseProperty] as $property) {
            $manager->persist($property);
        }

        $manager->persist(new PropertyImage($loft, 'fixtures/loft-main.jpg', 1, true));
        $manager->persist(new PropertyImage($loft, 'fixtures/loft-living-room.jpg', 2));
        $manager->persist(new PropertyImage($houseProperty, 'fixtures/house-main.jpg', 1, true));

        $manager->persist(new Booking(
            $bookingPending,
            $loft,
            $traveler,
            new \DateTimeImmutable('+15 days'),
            new \DateTimeImmutable('+18 days'),
            2,
            360,
            45,
            300,
            705,
        ));

        $manager->flush();
    }

    /** @param list<string> $roles */
    private function createUser(
        string $email,
        string $firstname,
        string $lastname,
        UserStatus $status,
        AgeVerificationStatus $ageVerificationStatus,
        Country $country,
        array $roles = ['ROLE_USER'],
    ): User {
        $user = new User($email, 'temporary-password', $firstname, $lastname, $status, $ageVerificationStatus, $country);
        $user->setRoles($roles);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'Password123!'));

        return $user;
    }
}
