<?php

namespace App\Tests;

use App\Entity\AgeVerificationStatus;
use App\Entity\Country;
use App\Entity\FavoriteProperty;
use App\Entity\Property;
use App\Entity\PropertyCategory;
use App\Entity\PropertyStatus;
use App\Entity\User;
use App\Entity\UserStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class FavoriteManagementTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->ensureReferences();
    }

    public function testUserCanAddFavoriteOnlyOnceAndRemoveIt(): void
    {
        $client = self::createClient();
        $host = $this->createUser('favorite-host-'.bin2hex(random_bytes(4)).'@example.com');
        $traveler = $this->createUser('favorite-traveler-'.bin2hex(random_bytes(4)).'@example.com');
        $property = $this->createProperty($host, 'Favori test '.bin2hex(random_bytes(3)));

        $client->loginUser($traveler);
        $payload = ['_token' => $this->csrfToken('favorite_property_'.$property->getId())];
        $client->request('POST', '/favorites/properties/'.$property->getId(), $payload);
        $client->request('POST', '/favorites/properties/'.$property->getId(), $payload);

        $favorites = $this->entityManager->getRepository(FavoriteProperty::class)->findBy([
            'user' => $traveler,
            'property' => $property,
        ]);
        self::assertCount(1, $favorites);

        $client->request('POST', '/favorites/properties/'.$property->getId().'/remove', $payload);
        $favorites = $this->entityManager->getRepository(FavoriteProperty::class)->findBy([
            'user' => $traveler,
            'property' => $property,
        ]);
        self::assertCount(0, $favorites);
    }

    public function testAnonymousFavoriteRequiresLogin(): void
    {
        $client = self::createClient();
        $property = $this->createProperty($this->createUser('anon-host-'.bin2hex(random_bytes(4)).'@example.com'), 'Favori protege');

        $client->request('POST', '/favorites/properties/'.$property->getId(), [
            '_token' => $this->csrfToken('favorite_property_'.$property->getId()),
        ]);

        self::assertResponseRedirects('http://localhost/connexion');
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
            'Un logement publie pour tester les favoris.',
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
            UserStatus::class => ['ACTIVE'],
            AgeVerificationStatus::class => ['VERIFIED'],
            Country::class => ['France'],
            PropertyCategory::class => ['Appartement'],
            PropertyStatus::class => ['PUBLISHED'],
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
