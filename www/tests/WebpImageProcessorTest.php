<?php

namespace App\Tests;

use App\Upload\WebpImageProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class WebpImageProcessorTest extends TestCase
{
    public function testPngImageIsConvertedToWebp(): void
    {
        self::markTestSkippedWhenWebpIsUnavailable();

        $sourcePath = tempnam(sys_get_temp_dir(), 'havre-webp-source-');
        self::assertIsString($sourcePath);

        $image = imagecreatetruecolor(32, 32);
        self::assertNotFalse($image);

        imagefill($image, 0, 0, imagecolorallocate($image, 47, 93, 80));
        imagepng($image, $sourcePath);
        imagedestroy($image);

        $outputPath = (new WebpImageProcessor())->process(new File($sourcePath));
        $info = getimagesize($outputPath);

        self::assertIsArray($info);
        self::assertSame(IMAGETYPE_WEBP, $info[2]);
        self::assertStringEndsWith('.webp', $outputPath);

        unlink($sourcePath);
        unlink($outputPath);
    }

    private static function markTestSkippedWhenWebpIsUnavailable(): void
    {
        if (!\extension_loaded('gd') || true !== (gd_info()['WebP Support'] ?? false)) {
            self::markTestSkipped('GD WebP support is not available.');
        }
    }
}
