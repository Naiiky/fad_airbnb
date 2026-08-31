<?php

namespace App\Tests;

use App\Entity\AgeVerificationStatus;
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

class AdminManagementTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->ensureReferences();
    }

    public function testUserCannotAccessAdminButAdminCan(): void
    {
        $client = self::createClient();

        $client->loginUser($this->createUser('plain-user-'.bin2hex(random_bytes(4)).'@example.com'));
        $client->request('GET', '/admin');
        self::assertResponseStatusCodeSame(403);

        $client->loginUser($this->createUser('admin-user-'.bin2hex(random_bytes(4)).'@example.com', ['ROLE_ADMIN']));
        $client->request('GET', '/admin');
        self::assertResponseIsSuccessful();
    }

    public function testAdminCanSuspendUserAndHideProperty(): void
    {
        $client = self::createClient();
        $admin = $this->createUser('admin-action-'.bin2hex(random_bytes(4)).'@example.com', ['ROLE_ADMIN']);
        $user = $this->createUser('to-suspend-'.bin2hex(random_bytes(4)).'@example.com');
        $property = $this->createProperty($user, 'A masquer admin '.bin2hex(random_bytes(3)));

        $client->loginUser($admin);
        $client->request('POST', '/admin/users/'.$user->getId().'/suspend', [
            '_token' => $this->csrfToken('suspend_user_'.$user->getId()),
        ]);
        self::assertResponseRedirects('/admin/users');
        $this->entityManager->refresh($user);
        self::assertSame('SUSPENDED', $user->getStatus()->getLabel());

        $client->request('POST', '/admin/properties/'.$property->getId().'/hide', [
            '_token' => $this->csrfToken('admin_hide_property_'.$property->getId()),
        ]);
        self::assertResponseRedirects('/admin/properties');
        $this->entityManager->refresh($property);
        self::assertSame('HIDDEN', $property->getStatus()->getLabel());
    }

    /** @param list<string> $roles */
    private function createUser(string $email, array $roles = ['ROLE_USER']): User
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
            ->setRoles($roles)
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
            'Un logement publie pour tester la moderation.',
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
