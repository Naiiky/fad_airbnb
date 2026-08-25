<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\PriceRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_price_property_day', columns: ['property_id', 'day'])]
#[ORM\Index(name: 'idx_price_property_day', columns: ['property_id', 'day'])]
class Price
{
    use IdTrait;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $day;

    #[ORM\Column]
    private int $priceNight;

    #[ORM\Column]
    private bool $isBlock = false;

    #[ORM\ManyToOne(inversedBy: 'prices')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Property $property;

    public function __construct(Property $property, \DateTimeImmutable $day, int $priceNight, bool $isBlock = false)
    {
        $this->initializeId();
        $this->property = $property;
        $this->day = $day;
        $this->priceNight = $priceNight;
        $this->isBlock = $isBlock;
    }
}
