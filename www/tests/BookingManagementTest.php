<?php

namespace App\Tests;

use App\Entity\AgeVerificationStatus;
use App\Entity\Booking;
use App\Entity\BookingStatus;
use App\Entity\Country;
use App\Entity\Property;
use App\Entity\PropertyCategory;
use App\Entity\PropertyStatus;
use App\Entity\User;
use App\Entity\UserStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class BookingManagementTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->ensureReferences();
    }

    public function testHostOnlySeesOwnBookingRequests(): void
    {
        $client = self::createClient();
        $host = $this->createUser('booking-host-'.bin2hex(random_bytes(4)).'@example.com');
        $otherHost = $this->createUser('booking-other-host-'.bin2hex(random_bytes(4)).'@example.com');
        $traveler = $this->createUser('booking-traveler-'.bin2hex(random_bytes(4)).'@example.com');
        $ownProperty = $this->createProperty($host, 'Maison demandee '.bin2hex(random_bytes(3)));
        $otherProperty = $this->createProperty($otherHost, 'Maison invisible '.bin2hex(random_bytes(3)));
        $this->createBooking($ownProperty, $traveler, 'PENDING');
        $this->createBooking($otherProperty, $traveler, 'PENDING');

        $client->loginUser($host);
        $client->request('GET', '/host/bookings');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', $ownProperty->getTitle());
        self::assertSelectorTextNotContains('body', $otherProperty->getTitle());
    }

    public function testHostCanAcceptPendingBookingWithCsrf(): void
    {
        $client = self::createClient();
        $host = $this->createUser('accept-host-'.bin2hex(random_bytes(4)).'@example.com');
        $traveler = $this->createUser('accept-traveler-'.bin2hex(random_bytes(4)).'@example.com');
        $booking = $this->createBooking($this->createProperty($host, 'Logement a accepter'), $traveler, 'PENDING');

        $client->loginUser($host);
        $client->request('POST', '/host/bookings/'.$booking->getId().'/accept', [
            '_token' => $this->csrfToken('accept_booking_'.$booking->getId()),
        ]);

        self::assertResponseRedirects('/host/bookings');

        $this->entityManager->refresh($booking);
        self::assertSame('ACCEPTED', $booking->getStatus()->getLabel());
    }

    public function testForeignHostCannotAcceptBooking(): void
    {
        $client = self::createClient();
        $host = $this->createUser('owner-host-'.bin2hex(random_bytes(4)).'@example.com');
        $foreignHost = $this->createUser('foreign-host-'.bin2hex(random_bytes(4)).'@example.com');
        $traveler = $this->createUser('foreign-traveler-'.bin2hex(random_bytes(4)).'@example.com');
        $booking = $this->createBooking($this->createProperty($host, 'Logement protege'), $traveler, 'PENDING');

        $client->loginUser($foreignHost);
        $client->request('POST', '/host/bookings/'.$booking->getId().'/accept', [
            '_token' => $this->csrfToken('accept_booking_'.$booking->getId()),
        ]);

        self::assertResponseStatusCodeSame(403);
    }

    public function testTravelerCanCancelOwnFutureBooking(): void
    {
        $client = self::createClient();
        $host = $this->createUser('cancel-host-'.bin2hex(random_bytes(4)).'@example.com');
        $traveler = $this->createUser('cancel-traveler-'.bin2hex(random_bytes(4)).'@example.com');
        $booking = $this->createBooking($this->createProperty($host, 'Logement a annuler'), $traveler, 'ACCEPTED');

        $client->loginUser($traveler);
        $client->request('POST', '/traveler/bookings/'.$booking->getId().'/cancel', [
            '_token' => $this->csrfToken('cancel_booking_'.$booking->getId()),
        ]);

        self::assertResponseRedirects('/traveler/bookings');

        $this->entityManager->refresh($booking);
        self::assertSame('CANCELLED', $booking->getStatus()->getLabel());
        self::assertNotNull($booking->getCancellationDate());
    }

    private function createBooking(Property $property, User $traveler, string $statusLabel): Booking
    {
        $booking = new Booking(
            $this->findReference(BookingStatus::class, $statusLabel),
            $property,
            $traveler,
            new \DateTimeImmutable('+20 days'),
            new \DateTimeImmutable('+23 days'),
            2,
            300,
            30,
            200,
            530,
        );

        $this->entityManager->persist($booking);
        $this->entityManager->flush();

        return $booking;
    }

    private function createUser(string $email): User
    {
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $user = new User(
            $email,
            'temporary-password',
            'Alice',
            'Martin',
            $this->findReference(UserStatus::class, 'ACTIVE'),
            $this->findReference(AgeVerificationStatus::class, 'VERIFIED'),
            $this->findReference(Country::class, 'France'),
        );
        $user
            ->setRoles(['ROLE_USER'])
            ->verifyEmail()
            ->acceptTerms(new \DateTimeImmutable('-1 day'))
            ->setProfile('Alice', 'Martin', birthDate: new \DateTimeImmutable('-30 years'))
            ->setPassword($passwordHasher->hashPassword($user, 'Password123!'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function createProperty(User $host, string $title): Property
    {
        $property = new Property(
            $host,
            $this->findReference(Country::class, 'France'),
            $this->findReference(PropertyCategory::class, 'Appartement'),
            $this->findReference(PropertyStatus::class, 'PUBLISHED'),
            $title,
            'Un logement publie pour tester les reservations.',
            '8 avenue des Pins',
            'Nantes',
            '44000',
            100,
        );
        $property
            ->updateCapacity(4, 2, 1, 2, 58)
            ->updateFees(200, 30, 100, 120)
            ->publish(new \DateTimeImmutable('-1 day'));

        $this->entityManager->persist($property);
        $this->entityManager->flush();

        return $property;
    }

    private function csrfToken(string $tokenId): string
    {
        return (string) static::getContainer()->get(CsrfTokenManagerInterface::class)->getToken($tokenId);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $className
     *
     * @return T
     */
    private function findReference(string $className, string $label): object
    {
        $reference = $this->entityManager->getRepository($className)->findOneBy(['label' => $label]);
        self::assertIsObject($reference);

        return $reference;
    }

    private function ensureReferences(): void
    {
        foreach ([
            UserStatus::class => ['ACTIVE', 'SUSPENDED'],
            AgeVerificationStatus::class => ['VERIFIED'],
            Country::class => ['France'],
            PropertyCategory::class => ['Appartement'],
            PropertyStatus::class => ['PUBLISHED', 'HIDDEN'],
            BookingStatus::class => ['PENDING', 'ACCEPTED', 'REJECTED', 'CANCELLED'],
        ] as $className => $labels) {
            foreach ($labels as $label) {
                $this->ensureReference($className, $label);
            }
        }

        $this->entityManager->flush();
    }

    /** @param class-string<object> $className */
    private function ensureReference(string $className, string $label): void
    {
        if (null !== $this->entityManager->getRepository($className)->findOneBy(['label' => $label])) {
            return;
        }

        $this->entityManager->persist(new $className($label));
    }
}
