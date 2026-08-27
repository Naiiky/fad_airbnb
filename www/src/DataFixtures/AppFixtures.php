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
        $portugal = new Country('Portugal');

        $apartment = new PropertyCategory('Appartement');
        $house = new PropertyCategory('Maison');
        $villa = new PropertyCategory('Villa');
        $studio = new PropertyCategory('Studio');
        $loftCategory = new PropertyCategory('Loft');
        $chalet = new PropertyCategory('Chalet');
        $privateRoom = new PropertyCategory('Chambre privee');

        $draft = new PropertyStatus('DRAFT');
        $published = new PropertyStatus('PUBLISHED');
        $hidden = new PropertyStatus('HIDDEN');

        $wifi = new Equipment('Wi-Fi');
        $kitchen = new Equipment('Cuisine equipee');
        $parking = new Equipment('Parking');
        $airConditioning = new Equipment('Climatisation');
        $pool = new Equipment('Piscine');
        $terrace = new Equipment('Terrasse');
        $workspace = new Equipment('Espace de travail');
        $washingMachine = new Equipment('Lave-linge');
        $seaView = new Equipment('Vue mer');

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
            $portugal,
            $apartment,
            $house,
            $villa,
            $studio,
            $loftCategory,
            $chalet,
            $privateRoom,
            $draft,
            $published,
            $hidden,
            $wifi,
            $kitchen,
            $parking,
            $airConditioning,
            $pool,
            $terrace,
            $workspace,
            $washingMachine,
            $seaView,
            $bookingPending,
            $bookingAccepted,
            $bookingRejected,
            $bookingCancelled,
        ] as $reference) {
            $manager->persist($reference);
        }

        $host = $this->createUser('host@example.com', 'Alice', 'Martin', $active, $verifiedAge, $france);
        $hostClara = $this->createUser('clara.host@example.com', 'Clara', 'Morel', $active, $verifiedAge, $france);
        $hostHugo = $this->createUser('hugo.host@example.com', 'Hugo', 'Rossi', $active, $verifiedAge, $italy);
        $hostInes = $this->createUser('ines.host@example.com', 'Ines', 'Garcia', $active, $verifiedAge, $spain);
        $hostMateo = $this->createUser('mateo.host@example.com', 'Mateo', 'Silva', $active, $verifiedAge, $portugal);
        $traveler = $this->createUser('traveler@example.com', 'Mehdi', 'Bernard', $active, $pendingAge, $france);
        $travelerEmma = $this->createUser('emma.traveler@example.com', 'Emma', 'Petit', $active, $verifiedAge, $france);
        $travelerNora = $this->createUser('nora.traveler@example.com', 'Nora', 'Lopez', $active, $verifiedAge, $spain);
        $admin = $this->createUser('admin@example.com', 'Admin', 'Airbnb', $active, $verifiedAge, $france, ['ROLE_ADMIN']);

        foreach ([$host, $hostClara, $hostHugo, $hostInes, $hostMateo, $traveler, $travelerEmma, $travelerNora, $admin] as $user) {
            $user->acceptTerms(new \DateTimeImmutable('-1 day'));
            $user->addLanguage($french);
            $manager->persist($user);
        }

        $loft = $this->createProperty(
            $host,
            $france,
            $loftCategory,
            $published,
            'Loft lumineux centre-ville',
            'Grand loft de demonstration proche des transports, parfait pour un sejour urbain.',
            '12 rue de Paris',
            'Lyon',
            '69002',
            120,
            [4, 2, 1, 2, 65],
            [300, 45, 120, 140],
            [$wifi, $kitchen, $workspace],
            true,
            24,
            4.8,
            new \DateTimeImmutable('-15 days'),
        );

        $properties = [
            $loft,
            $this->createProperty($host, $france, $house, $draft, 'Maison calme avec jardin', 'Maison familiale proche des parcs et du centre.', '8 avenue des Pins', 'Nantes', '44000', 95, [6, 3, 2, 4, 110], [500, 70, 95, 115], [$parking, $kitchen, $washingMachine], true, 0, 0.0),
            $this->createProperty($host, $france, $studio, $published, 'Studio compact pres de la gare', 'Studio simple et fonctionnel pour courts sejours professionnels.', '4 place Carnot', 'Lille', '59000', 58, [2, 1, 1, 1, 24], [150, 25, 58, 68], [$wifi, $workspace], false, 11, 4.4, new \DateTimeImmutable('-13 days')),
            $this->createProperty($hostClara, $france, $apartment, $published, 'Appartement familial aux Chartrons', 'Appartement calme avec pieces spacieuses dans un quartier vivant.', '22 rue Notre-Dame', 'Bordeaux', '33000', 132, [5, 2, 1, 3, 78], [350, 55, 132, 155], [$wifi, $kitchen, $washingMachine], true, 18, 4.7, new \DateTimeImmutable('-12 days')),
            $this->createProperty($hostClara, $france, $villa, $published, 'Villa avec piscine en Provence', 'Villa lumineuse avec jardin, piscine et grande terrasse.', '45 chemin des Oliviers', 'Aix-en-Provence', '13100', 260, [8, 4, 3, 5, 180], [900, 120, 260, 320], [$wifi, $kitchen, $parking, $pool, $terrace], false, 32, 4.9, new \DateTimeImmutable('-11 days')),
            $this->createProperty($hostClara, $france, $privateRoom, $hidden, 'Chambre privee proche universite', 'Chambre confortable chez l habitant avec bureau et acces cuisine.', '7 rue des Facultes', 'Montpellier', '34090', 42, [1, 1, 1, 1, 16], [80, 15, 42, 50], [$wifi, $workspace, $kitchen], false, 3, 4.2),
            $this->createProperty($hostHugo, $italy, $apartment, $published, 'Appartement avec balcon a Rome', 'Appartement central avec balcon, ideal pour explorer la ville a pied.', '16 via Roma', 'Rome', '00184', 145, [4, 2, 1, 2, 62], [350, 45, 145, 170], [$wifi, $kitchen, $airConditioning, $terrace], false, 21, 4.6, new \DateTimeImmutable('-10 days')),
            $this->createProperty($hostHugo, $italy, $villa, $published, 'Maison de vacances en Toscane', 'Maison de campagne avec piscine et vue sur les collines.', '9 strada del Vino', 'Sienne', '53100', 210, [7, 3, 2, 4, 140], [700, 90, 210, 255], [$wifi, $kitchen, $parking, $pool], true, 16, 4.8, new \DateTimeImmutable('-9 days')),
            $this->createProperty($hostHugo, $italy, $studio, $draft, 'Studio calme a Florence', 'Petit studio en preparation pres des musees.', '3 via dei Servi', 'Florence', '50122', 72, [2, 1, 1, 1, 28], [180, 25, 72, 82], [$wifi, $airConditioning], false, 0, 0.0),
            $this->createProperty($hostInes, $spain, $apartment, $published, 'Appartement plage a Barcelone', 'Appartement lumineux a quelques minutes de la plage et du metro.', '18 carrer de la Marina', 'Barcelone', '08005', 138, [4, 2, 1, 2, 58], [300, 50, 138, 165], [$wifi, $kitchen, $airConditioning, $seaView], false, 27, 4.7, new \DateTimeImmutable('-8 days')),
            $this->createProperty($hostInes, $spain, $villa, $published, 'Villa blanche a Malaga', 'Villa moderne avec piscine, parking et terrasse ombragee.', '31 calle Limonar', 'Malaga', '29016', 245, [8, 4, 3, 5, 165], [850, 110, 245, 295], [$wifi, $kitchen, $parking, $pool, $terrace], true, 14, 4.9, new \DateTimeImmutable('-7 days')),
            $this->createProperty($hostInes, $spain, $loftCategory, $published, 'Loft industriel a Madrid', 'Loft ouvert avec bureau, climatisation et cuisine equipee.', '5 calle de Atocha', 'Madrid', '28012', 118, [3, 1, 1, 2, 54], [250, 40, 118, 135], [$wifi, $kitchen, $workspace, $airConditioning], false, 9, 4.5, new \DateTimeImmutable('-6 days')),
            $this->createProperty($hostMateo, $portugal, $apartment, $published, 'T2 avec vue sur le Douro', 'Appartement confortable avec vue degagee et acces rapide au centre.', '11 rua das Flores', 'Porto', '4050-263', 112, [4, 2, 1, 2, 60], [280, 40, 112, 130], [$wifi, $kitchen, $seaView], false, 19, 4.6, new \DateTimeImmutable('-5 days')),
            $this->createProperty($hostMateo, $portugal, $house, $published, 'Maison de pecheur a Faro', 'Maison renovee pres de la marina, adaptee aux familles.', '2 rua do Sol', 'Faro', '8000-161', 105, [5, 2, 2, 3, 72], [300, 45, 105, 128], [$wifi, $kitchen, $washingMachine, $terrace], true, 12, 4.5, new \DateTimeImmutable('-4 days')),
            $this->createProperty($hostMateo, $france, $chalet, $published, 'Chalet cosy a Chamonix', 'Chalet chaleureux avec vue montagne, parking et grande piece de vie.', '19 route des Praz', 'Chamonix', '74400', 185, [6, 3, 2, 4, 105], [600, 85, 185, 230], [$wifi, $kitchen, $parking, $washingMachine], true, 30, 4.9, new \DateTimeImmutable('-3 days')),
        ];

        foreach ($properties as $property) {
            $this->addFixtureImages($property);
            $manager->persist($property);
        }

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
        $manager->persist(new Booking(
            $bookingAccepted,
            $properties[4],
            $travelerEmma,
            new \DateTimeImmutable('+5 days'),
            new \DateTimeImmutable('+9 days'),
            2,
            1040,
            120,
            900,
            2060,
        ));
        $manager->persist(new Booking(
            $bookingCancelled,
            $properties[9],
            $travelerNora,
            new \DateTimeImmutable('+22 days'),
            new \DateTimeImmutable('+25 days'),
            2,
            414,
            50,
            300,
            764,
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

    /**
     * @param array{0: int, 1: int, 2: int, 3: int, 4: int} $capacity
     * @param array{0: int, 1: int, 2: int, 3: int} $fees
     * @param list<Equipment> $equipments
     */
    private function createProperty(
        User $host,
        Country $country,
        PropertyCategory $category,
        PropertyStatus $status,
        string $title,
        string $description,
        string $address,
        string $city,
        string $zipCode,
        int $nightlyPrice,
        array $capacity,
        array $fees,
        array $equipments,
        bool $petsAllowed = false,
        int $reviewCount = 0,
        float $averageRating = 0.0,
        ?\DateTimeImmutable $publishedAt = null,
    ): Property {
        $property = new Property($host, $country, $category, $status, $title, $description, $address, $city, $zipCode, $nightlyPrice);
        $property
            ->updateCapacity($capacity[0], $capacity[1], $capacity[2], $capacity[3], $capacity[4])
            ->updateFees($fees[0], $fees[1], $fees[2], $fees[3])
            ->setPetsAllowed($petsAllowed)
            ->setReviewSummary($reviewCount, $averageRating);

        foreach ($equipments as $equipment) {
            $property->addEquipment($equipment);
        }

        if (null !== $publishedAt) {
            $property->publish($publishedAt);
        }

        return $property;
    }

    private function addFixtureImages(Property $property): void
    {
        $property->addImage('fixtures/property-1-cover.svg');
        $property->addImage('fixtures/property-1-living.svg');
        $property->addImage('fixtures/property-1-detail.svg');
    }
}
