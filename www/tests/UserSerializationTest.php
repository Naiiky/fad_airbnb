<?php

namespace App\Tests;

use App\Entity\AgeVerificationStatus;
use App\Entity\Country;
use App\Entity\User;
use App\Entity\UserStatus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class UserSerializationTest extends TestCase
{
    public function testAvatarFileIsNotSerializedWithAuthenticatedUser(): void
    {
        $filePath = tempnam(sys_get_temp_dir(), 'havre-avatar-session-');
        self::assertIsString($filePath);
        file_put_contents($filePath, 'temporary image contents');

        $user = new User(
            'avatar-session@example.com',
            'hashed-password',
            'Avatar',
            'Session',
            new UserStatus('ACTIVE'),
            new AgeVerificationStatus('VERIFIED'),
            new Country('France'),
        );
        $user->setAvatarFile(new File($filePath));

        $serialized = serialize($user);
        $unserialized = unserialize($serialized);

        self::assertInstanceOf(User::class, $unserialized);
        self::assertSame('avatar-session@example.com', $unserialized->getUserIdentifier());
        self::assertNull($unserialized->getAvatarFile());

        unlink($filePath);
    }
}
