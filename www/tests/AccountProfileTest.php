<?php

namespace App\Tests;

use App\Entity\AgeVerificationStatus;
use App\Entity\Country;
use App\Entity\Language;
use App\Entity\User;
use App\Entity\UserStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountProfileTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->ensureReferences();
    }

    public function testAnonymousUserIsRedirectedToLogin(): void
    {
        $client = self::createClient();

        $client->request('GET', '/account');

        self::assertResponseRedirects('/connexion');
    }

    public function testAuthenticatedUserCanViewAccount(): void
    {
        $client = self::createClient();
        $user = $this->createUser('account-view-'.bin2hex(random_bytes(4)).'@example.com');
        $user
            ->setProfile('Camille', 'Morel', '+33 6 12 34 56 78', bio: 'Voyageuse calme, adepte des maisons lumineuses.', city: 'Lyon', birthDate: new \DateTimeImmutable('1992-04-10'))
            ->setBio('Voyageuse calme, adepte des maisons lumineuses.')
            ->addLanguage($this->findReference(Language::class, 'Français'));
        $this->entityManager->flush();

        $client->loginUser($user);
        $client->request('GET', '/account');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Bonjour Camille');
        self::assertSelectorTextContains('body', '+33 6 12 34 56 78');
        self::assertSelectorTextContains('body', ((new \DateTimeImmutable('1992-04-10'))->diff(new \DateTimeImmutable())->y).' ans');
        self::assertSelectorTextContains('body', 'ACTIVE');
        self::assertSelectorTextContains('body', 'Français');
    }

    public function testAuthenticatedUserCanUpdateProfile(): void
    {
        $client = self::createClient();
        $user = $this->createUser('account-edit-'.bin2hex(random_bytes(4)).'@example.com');
        $france = $this->findReference(Country::class, 'France');
        $belgium = $this->findReference(Country::class, 'Belgique');
        $french = $this->findReference(Language::class, 'Français');
        $english = $this->findReference(Language::class, 'Anglais');
        $user->addLanguage($french);
        $this->entityManager->flush();

        $client->loginUser($user);
        $crawler = $client->request('GET', '/account/edit');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Modifier mon profil');

        $client->submit($crawler->selectButton('Enregistrer le profil')->form([
            'profile_form[firstname]' => 'Camille',
            'profile_form[lastname]' => 'Durand',
            'profile_form[phone]' => '+32 470 12 34 56',
            'profile_form[avatarFile][file]' => $this->createProfileImage(),
            'profile_form[bio]' => 'Je cherche des séjours calmes avec une belle lumière.',
            'profile_form[address]' => '12 rue des Hôtes',
            'profile_form[city]' => 'Bruxelles',
            'profile_form[zipCode]' => '1000',
            'profile_form[country]' => $belgium->getId(),
            'profile_form[languages]' => [$french->getId(), $english->getId()],
        ]));

        self::assertResponseRedirects('/account');

        $this->entityManager->refresh($user);
        self::assertSame('Camille', $user->getFirstname());
        self::assertSame('Durand', $user->getLastname());
        self::assertSame('+32 470 12 34 56', $user->getPhone());
        self::assertNotNull($user->getAvatar());
        self::assertStringEndsWith('.webp', $user->getAvatar());
        self::assertSame('Je cherche des séjours calmes avec une belle lumière.', $user->getBio());
        self::assertSame('12 rue des Hôtes', $user->getAddress());
        self::assertSame('Bruxelles', $user->getCity());
        self::assertSame('1000', $user->getZipCode());
        self::assertSame($belgium->getId(), $user->getCountry()->getId());
        self::assertNotSame($france->getId(), $user->getCountry()->getId());

        $languageLabels = array_map(
            static fn ($userLanguage) => $userLanguage->getLanguage()->getLabel(),
            $user->getUserLanguages()->toArray(),
        );
        self::assertContains('Français', $languageLabels);
        self::assertContains('Anglais', $languageLabels);
    }

    private function createUser(string $email): User
    {
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $status = $this->findReference(UserStatus::class, 'ACTIVE');
        $ageStatus = $this->findReference(AgeVerificationStatus::class, 'VERIFIED');
        $country = $this->findReference(Country::class, 'France');

        $user = new User($email, 'temporary-password', 'Camille', 'Morel', $status, $ageStatus, $country);
        $user
            ->setRoles(['ROLE_USER'])
            ->verifyEmail()
            ->acceptTerms(new \DateTimeImmutable('-1 day'))
            ->setPassword($passwordHasher->hashPassword($user, 'Password123!'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function createProfileImage(): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'havre-profile-test-');
        self::assertIsString($path);

        $image = imagecreatetruecolor(32, 32);
        self::assertNotFalse($image);

        imagefill($image, 0, 0, imagecolorallocate($image, 47, 93, 80));
        imagepng($image, $path);
        imagedestroy($image);

        return new UploadedFile($path, 'avatar.png', 'image/png', null, true);
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
        $this->ensureReference(AgeVerificationStatus::class, 'VERIFIED');
        $this->ensureReference(Country::class, 'France');
        $this->ensureReference(Country::class, 'Belgique');
        $this->ensureReference(Language::class, 'Français');
        $this->ensureReference(Language::class, 'Anglais');
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
