<?php

namespace App\Tests;

use App\Entity\AgeVerificationStatus;
use App\Entity\Country;
use App\Entity\Equipment;
use App\Entity\Property;
use App\Entity\PropertyCategory;
use App\Entity\PropertyStatus;
use App\Entity\User;
use App\Entity\UserStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class HostPropertyManagementTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->ensureReferences();
    }

    public function testHostCanCreateDraftProperty(): void
    {
        $client = self::createClient();
        $host = $this->createUser('host-create-'.bin2hex(random_bytes(4)).'@example.com');
        $country = $this->findReference(Country::class, 'France');
        $category = $this->findReference(PropertyCategory::class, 'Appartement');
        $equipment = $this->findReference(Equipment::class, 'Wi-Fi');
        $title = 'Atelier lumineux '.bin2hex(random_bytes(3));

        $client->loginUser($host);
        $crawler = $client->request('GET', '/host/properties/new');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Créer un logement');

        $client->submit($crawler->selectButton('Créer le brouillon')->form([
            'property_form[title]' => $title,
            'property_form[description]' => 'Un logement lumineux, calme et entierement equipe pour un sejour confortable.',
            'property_form[maxGuest]' => '3',
            'property_form[bedrooms]' => '1',
            'property_form[bathrooms]' => '1',
            'property_form[beds]' => '2',
            'property_form[areaM2]' => '42',
            'property_form[address]' => '14 rue des Lilas',
            'property_form[city]' => 'Lyon',
            'property_form[zipCode]' => '69007',
            'property_form[country]' => $country->getId(),
            'property_form[category]' => $category->getId(),
            'property_form[equipments]' => [$equipment->getId()],
            'property_form[petsAllowed]' => '1',
            'property_form[nightlyPrice]' => '98',
            'property_form[weekendPrice]' => '120',
            'property_form[cleaningFee]' => '30',
            'property_form[deposit]' => '250',
        ]));

        self::assertResponseRedirects('/host/properties');

        $property = $this->entityManager->getRepository(Property::class)->findOneBy(['title' => $title]);
        self::assertInstanceOf(Property::class, $property);
        self::assertSame($host->getId(), $property->getUser()->getId());
        self::assertSame('DRAFT', $property->getStatus()->getLabel());
        self::assertTrue($property->isPetsAllowed());
        self::assertCount(1, $property->getPropertyEquipments());
    }

    public function testHostListOnlyShowsOwnedProperties(): void
    {
        $client = self::createClient();
        $host = $this->createUser('host-list-'.bin2hex(random_bytes(4)).'@example.com');
        $otherHost = $this->createUser('host-other-'.bin2hex(random_bytes(4)).'@example.com');
        $ownTitle = 'Maison hote '.bin2hex(random_bytes(3));
        $otherTitle = 'Maison autre '.bin2hex(random_bytes(3));
        $this->createProperty($host, $ownTitle);
        $this->createProperty($otherHost, $otherTitle);

        $client->loginUser($host);
        $client->request('GET', '/host/properties');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', $ownTitle);
        self::assertSelectorTextNotContains('body', $otherTitle);
    }

    public function testForeignUserCannotEditProperty(): void
    {
        $client = self::createClient();
        $host = $this->createUser('host-owner-'.bin2hex(random_bytes(4)).'@example.com');
        $foreignUser = $this->createUser('host-foreign-'.bin2hex(random_bytes(4)).'@example.com');
        $property = $this->createProperty($host, 'Logement prive '.bin2hex(random_bytes(3)));

        $client->loginUser($foreignUser);
        $client->request('GET', '/host/properties/'.$property->getId().'/edit');

        self::assertResponseStatusCodeSame(403);
    }

    public function testHostCanPublishDraftPropertyWithCsrf(): void
    {
        $client = self::createClient();
        $host = $this->createUser('host-publish-'.bin2hex(random_bytes(4)).'@example.com');
        $property = $this->createProperty($host, 'Brouillon publiable '.bin2hex(random_bytes(3)));

        $client->loginUser($host);
        $client->request('POST', '/host/properties/'.$property->getId().'/publish', [
            '_token' => $this->csrfToken('publish_property_'.$property->getId()),
        ]);

        self::assertResponseRedirects('/host/properties');

        $this->entityManager->refresh($property);
        self::assertSame('PUBLISHED', $property->getStatus()->getLabel());
        self::assertNotNull($property->getPublishedAt());
    }

    public function testHostCanHidePropertyWithCsrf(): void
    {
        $client = self::createClient();
        $host = $this->createUser('host-hide-'.bin2hex(random_bytes(4)).'@example.com');
        $property = $this->createProperty($host, 'Logement a masquer '.bin2hex(random_bytes(3)), 'PUBLISHED');

        $client->loginUser($host);
        $client->request('POST', '/host/properties/'.$property->getId().'/hide', [
            '_token' => $this->csrfToken('hide_property_'.$property->getId()),
        ]);

        self::assertResponseRedirects('/host/properties');

        $this->entityManager->refresh($property);
        self::assertSame('HIDDEN', $property->getStatus()->getLabel());
    }

    private function createUser(string $email, bool $adult = true): User
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
            ->setProfile('Alice', 'Martin', birthDate: new \DateTimeImmutable($adult ? '-30 years' : '-16 years'))
            ->setPassword($passwordHasher->hashPassword($user, 'Password123!'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function createProperty(User $host, string $title, string $statusLabel = 'DRAFT'): Property
    {
        $property = new Property(
            $host,
            $this->findReference(Country::class, 'France'),
            $this->findReference(PropertyCategory::class, 'Appartement'),
            $this->findReference(PropertyStatus::class, $statusLabel),
            $title,
            'Un logement complet, confortable et pret pour la publication.',
            '8 avenue des Pins',
            'Nantes',
            '44000',
            110,
        );
        $property
            ->updateCapacity(4, 2, 1, 2, 58)
            ->updateFees(200, 35, 110, 130);

        $this->entityManager->persist($property);
        $this->entityManager->flush();

        return $property;
    }

    private function csrfToken(string $tokenId): string
    {
        return (string) static::getContainer()
            ->get(CsrfTokenManagerInterface::class)
            ->getToken($tokenId);
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
        $this->ensureReference(PropertyCategory::class, 'Appartement');
        $this->ensureReference(PropertyStatus::class, 'DRAFT');
        $this->ensureReference(PropertyStatus::class, 'PUBLISHED');
        $this->ensureReference(PropertyStatus::class, 'HIDDEN');
        $this->ensureReference(Equipment::class, 'Wi-Fi');
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
