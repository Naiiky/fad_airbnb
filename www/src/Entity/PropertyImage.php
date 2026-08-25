<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\PropertyImageRepository::class)]
class PropertyImage
{
    use IdTrait;

    #[ORM\Column]
    private string $image;

    #[ORM\Column]
    private int $displayOrder;

    #[ORM\Column]
    private bool $isMain = false;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Property $property;

    public function __construct(Property $property, string $image, int $displayOrder = 0, bool $isMain = false)
    {
        $this->initializeId();
        $this->property = $property;
        $this->image = $image;
        $this->displayOrder = $displayOrder;
        $this->isMain = $isMain;
    }
}
