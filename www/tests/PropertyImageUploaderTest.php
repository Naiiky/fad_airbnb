<?php

namespace App\Tests;

use App\Entity\AgeVerificationStatus;
use App\Entity\Country;
use App\Entity\Property;
use App\Entity\PropertyCategory;
use App\Entity\PropertyStatus;
use App\Entity\User;
use App\Entity\UserStatus;
use App\Upload\PropertyImageUploader;
use App\Upload\WebpImageProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PropertyImageUploaderTest extends TestCase
{
    public function testUploadedImageIsStoredAsRandomWebpPath(): void
    {
        self::markTestSkippedWhenWebpIsUnavailable();

        $sourcePath = $this->createPngImage();
        $uploadRoot = sys_get_temp_dir().'/havre-property-uploads-'.bin2hex(random_bytes(4));
        $property = $this->createProperty();

        $uploader = new PropertyImageUploader(new WebpImageProcessor(), $uploadRoot);
        $storedPath = $uploader->upload($property, new UploadedFile($sourcePath, 'photo.png', 'image/png', null, true));

        self::assertMatchesRegularExpression('/^'.preg_quote((string) $property->getId(), '/').'\/[a-f0-9]{32}\.webp$/', $storedPath);
        self::assertFileExists($uploadRoot.'/'.$storedPath);
        self::assertSame('/uploads/properties/'.$storedPath, $uploader->publicPath($storedPath));

        $uploader->remove($storedPath);
        self::assertFileDoesNotExist($uploadRoot.'/'.$storedPath);
        @unlink($sourcePath);
        @rmdir($uploadRoot.'/'.$property->getId());
        @rmdir($uploadRoot);
    }

    public function testInvalidMimeTypeIsRejected(): void
    {
        $sourcePath = tempnam(sys_get_temp_dir(), 'havre-not-image-');
        self::assertIsString($sourcePath);
        file_put_contents($sourcePath, 'not an image');

        $uploader = new PropertyImageUploader(new WebpImageProcessor(), sys_get_temp_dir());

        $this->expectException(\RuntimeException::class);
        try {
            $uploader->upload($this->createProperty(), new UploadedFile($sourcePath, 'payload.txt', 'text/plain', null, true));
        } finally {
            @unlink($sourcePath);
        }
    }

    private function createPngImage(): string
    {
        $sourcePath = tempnam(sys_get_temp_dir(), 'havre-property-image-');
        self::assertIsString($sourcePath);

        $image = imagecreatetruecolor(64, 48);
        self::assertNotFalse($image);

        imagefill($image, 0, 0, imagecolorallocate($image, 47, 93, 80));
        imagepng($image, $sourcePath);
        imagedestroy($image);

        return $sourcePath;
    }

    private function createProperty(): Property
    {
        return new Property(
            new User(
                'host@example.com',
                'hashed-password',
                'Alice',
                'Martin',
                new UserStatus('ACTIVE'),
                new AgeVerificationStatus('VERIFIED'),
                new Country('France'),
            ),
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

    private static function markTestSkippedWhenWebpIsUnavailable(): void
    {
        if (!\extension_loaded('gd') || true !== (gd_info()['WebP Support'] ?? false)) {
            self::markTestSkipped('GD WebP support is not available.');
        }
    }
}
