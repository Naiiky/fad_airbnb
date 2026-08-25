<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private ?string $id = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    private function initializeId(): void
    {
        $this->id ??= self::generateId();
    }

    private static function generateId(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
