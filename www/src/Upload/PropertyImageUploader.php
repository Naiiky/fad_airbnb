<?php

namespace App\Upload;

use App\Entity\Property;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PropertyImageUploader
{
    private const MAX_SIZE = 5_242_880;
    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    public function __construct(
        private readonly WebpImageProcessor $imageProcessor,
        #[Autowire('%kernel.project_dir%/public/uploads/properties')]
        private readonly string $uploadDirectory,
    ) {
    }

    public function upload(Property $property, UploadedFile $file): string
    {
        $this->validate($file);

        $propertyId = $property->getId();
        if (null === $propertyId) {
            throw new \RuntimeException('Le logement doit etre persiste avant de recevoir des images.');
        }

        $targetDirectory = $this->uploadDirectory.'/'.$propertyId;
        if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0755, true) && !is_dir($targetDirectory)) {
            throw new \RuntimeException("Le dossier d'upload du logement n'a pas pu etre cree.");
        }

        $webpPath = $this->imageProcessor->process($file, 1920, 1280);
        $fileName = bin2hex(random_bytes(16)).'.webp';

        try {
            if (!rename($webpPath, $targetDirectory.'/'.$fileName)) {
                throw new FileException("L'image n'a pas pu etre enregistree.");
            }
        } finally {
            if (is_file($webpPath)) {
                @unlink($webpPath);
            }
        }

        return $propertyId.'/'.$fileName;
    }

    public function remove(string $storedPath): void
    {
        $path = $this->resolveStoredPath($storedPath);
        if (null !== $path && is_file($path)) {
            @unlink($path);
        }
    }

    public function publicPath(string $storedPath): string
    {
        return '/uploads/properties/'.ltrim($storedPath, '/');
    }

    private function validate(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new \RuntimeException("L'image envoyee est invalide.");
        }

        if ($file->getSize() > self::MAX_SIZE) {
            throw new \RuntimeException("L'image ne doit pas depasser 5 Mo.");
        }

        $mimeType = $file->getMimeType();
        if (null === $mimeType || !\in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new \RuntimeException('Veuillez selectionner une image JPEG, PNG ou WebP.');
        }

        $info = @getimagesize($file->getPathname());
        if (false === $info || !\in_array($info['mime'] ?? null, self::ALLOWED_MIME_TYPES, true)) {
            throw new \RuntimeException("Le fichier selectionne n'est pas une image valide.");
        }
    }

    private function resolveStoredPath(string $storedPath): ?string
    {
        $normalized = trim(str_replace('\\', '/', $storedPath), '/');
        if ('' === $normalized || str_contains($normalized, '..')) {
            return null;
        }

        $base = rtrim(str_replace('\\', '/', $this->uploadDirectory), '/');
        $path = $base.'/'.$normalized;

        return str_starts_with($path, $base.'/') ? $path : null;
    }
}
