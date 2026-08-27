<?php

namespace App\Upload;

use Symfony\Component\HttpFoundation\File\File;

class WebpImageProcessor
{
    public function __construct(
        private readonly int $quality = 82,
        private readonly int $maxWidth = 512,
        private readonly int $maxHeight = 512,
    ) {
    }

    public function process(File $source, ?int $maxWidth = null, ?int $maxHeight = null): string
    {
        if (!\extension_loaded('gd') || true !== (gd_info()['WebP Support'] ?? false)) {
            throw new \RuntimeException('La conversion WebP n’est pas disponible sur ce serveur.');
        }

        $contents = file_get_contents($source->getPathname());
        if (false === $contents) {
            throw new \RuntimeException("L'image n'a pas pu être lue.");
        }

        $image = imagecreatefromstring($contents);
        if (false === $image) {
            throw new \RuntimeException("L'image n'a pas pu être décodée.");
        }

        try {
            $width = imagesx($image);
            $height = imagesy($image);
            $targetMaxWidth = $maxWidth ?? $this->maxWidth;
            $targetMaxHeight = $maxHeight ?? $this->maxHeight;
            $ratio = min(1, $targetMaxWidth / $width, $targetMaxHeight / $height);
            $targetWidth = max(1, (int) floor($width * $ratio));
            $targetHeight = max(1, (int) floor($height * $ratio));

            $target = imagecreatetruecolor($targetWidth, $targetHeight);
            if (false === $target) {
                throw new \RuntimeException("L'image n'a pas pu être préparée.");
            }

            try {
                imagealphablending($target, false);
                imagesavealpha($target, true);

                if (!imagecopyresampled($target, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height)) {
                    throw new \RuntimeException("L'image n'a pas pu être redimensionnée.");
                }

                $targetPath = tempnam(sys_get_temp_dir(), 'havre-avatar-');
                if (false === $targetPath) {
                    throw new \RuntimeException("L'image temporaire n'a pas pu être créée.");
                }

                $webpPath = $targetPath.'.webp';
                if (!imagewebp($target, $webpPath, $this->quality)) {
                    @unlink($targetPath);
                    throw new \RuntimeException("L'image n'a pas pu être convertie en WebP.");
                }
                @unlink($targetPath);

                $info = getimagesize($webpPath);
                if (false === $info || IMAGETYPE_WEBP !== $info[2]) {
                    @unlink($webpPath);
                    throw new \RuntimeException("Le fichier généré n'est pas un WebP valide.");
                }

                return $webpPath;
            } finally {
                imagedestroy($target);
            }
        } finally {
            imagedestroy($image);
        }
    }
}
