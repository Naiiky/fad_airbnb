<?php

namespace App\Tests;

use App\Entity\AgeVerificationStatus;
use App\Entity\Country;
use App\Entity\User;
use App\Entity\UserStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthenticationFlowTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->ensureReferences();
    }

    public function testRegistrationCreatesActiveVerifiedUser(): void
    {
        $client = self::createClient();
        $email = 'new-user-'.bin2hex(random_bytes(4)).'@example.com';
        $crawler = $client->request('GET', '/inscription');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Rejoignez-nous');

        $client->submit($crawler->selectButton('Créer mon compte')->form([
            'registration_form[firstname]' => 'Jeanne',
            'registration_form[lastname]' => 'Martin',
            'registration_form[birthDate]' => '1990-05-12',
            'registration_form[email]' => $email,
            'registration_form[plainPassword][first]' => 'Password123!',
            'registration_form[plainPassword][second]' => 'Password123!',
            'registration_form[termsAccepted]' => '1',
            'registration_form[privacyAccepted]' => '1',
        ]));

        self::assertResponseRedirects('/connexion');

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        self::assertInstanceOf(User::class, $user);
        self::assertSame(['ROLE_USER'], $user->getRoles());
        self::assertTrue($user->isEmailVerified());
        self::assertNotNull($user->getTermAcceptedAt());
        self::assertSame('ACTIVE', $user->getStatus()->getLabel());
        self::assertSame('1990-05-12', $user->getBirthDate()?->format('Y-m-d'));
    }

    public function testUnderageRegistrationIsRejected(): void
    {
        $client = self::createClient();
        $email = 'minor-'.bin2hex(random_bytes(4)).'@example.com';
        $crawler = $client->request('GET', '/inscription');

        $client->submit($crawler->selectButton('Créer mon compte')->form([
            'registration_form[firstname]' => 'Lina',
            'registration_form[lastname]' => 'Petit',
            'registration_form[birthDate]' => (new \DateTimeImmutable('-17 years'))->format('Y-m-d'),
            'registration_form[email]' => $email,
            'registration_form[plainPassword][first]' => 'Password123!',
            'registration_form[plainPassword][second]' => 'Password123!',
            'registration_form[termsAccepted]' => '1',
            'registration_form[privacyAccepted]' => '1',
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('body', '18 ans révolus');
        self::assertNull($this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]));
    }

    public function testLoginWithValidCredentialsStartsSession(): void
    {
        $client = self::createClient();
        $email = 'login-'.bin2hex(random_bytes(4)).'@example.com';
        $this->createUser($email, 'Password123!');
        $crawler = $client->request('GET', '/connexion');

        $client->submit($crawler->selectButton('Connexion')->form([
            '_username' => $email,
            '_password' => 'Password123!',
        ]));

        self::assertResponseRedirects('/');

        $client->followRedirect();
        self::assertSelectorTextContains('body', $email);
    }

    public function testSuspendedUserCannotLogin(): void
    {
        $client = self::createClient();
        $email = 'suspended-'.bin2hex(random_bytes(4)).'@example.com';
        $this->createUser($email, 'Password123!', 'SUSPENDED');
        $crawler = $client->request('GET', '/connexion');

        $client->submit($crawler->selectButton('Connexion')->form([
            '_username' => $email,
            '_password' => 'Password123!',
        ]));

        self::assertResponseRedirects('/connexion');

        $client->followRedirect();
        self::assertSelectorTextContains('body', 'Votre compte est suspendu.');
    }

    public function testLogoutRedirectsToLogin(): void
    {
        $client = self::createClient();
        $email = 'logout-'.bin2hex(random_bytes(4)).'@example.com';
        $user = $this->createUser($email, 'Password123!');

        $client->loginUser($user);
        $client->request('GET', '/deconnexion');

        self::assertResponseRedirects('/connexion');
    }

    private function createUser(string $email, string $plainPassword, string $statusLabel = 'ACTIVE'): User
    {
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $status = $this->findReference(UserStatus::class, $statusLabel);
        $ageStatus = $this->findReference(AgeVerificationStatus::class, 'VERIFIED');
        $country = $this->findReference(Country::class, 'France');

        $user = new User($email, 'temporary-password', 'Test', 'User', $status, $ageStatus, $country);
        $user
            ->setRoles(['ROLE_USER'])
            ->verifyEmail()
            ->acceptTerms(new \DateTimeImmutable('-1 day'))
            ->setPassword($passwordHasher->hashPassword($user, $plainPassword));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
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
        $this->ensureReference(UserStatus::class, 'ACTIVE');
        $this->ensureReference(UserStatus::class, 'SUSPENDED');
        $this->ensureReference(AgeVerificationStatus::class, 'PENDING');
        $this->ensureReference(AgeVerificationStatus::class, 'VERIFIED');
        $this->ensureReference(Country::class, 'France');
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
