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

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(int $displayOrder): self
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    public function isMain(): bool
    {
        return $this->isMain;
    }

    public function setMain(bool $isMain): self
    {
        $this->isMain = $isMain;

        return $this;
    }

    public function getProperty(): Property
    {
        return $this->property;
    }
}
