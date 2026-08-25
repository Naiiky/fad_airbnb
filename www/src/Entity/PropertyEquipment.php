<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\PropertyEquipmentRepository::class)]
class PropertyEquipment
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'propertyEquipments')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Property $property;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'propertyEquipments')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private Equipment $equipment;

    public function __construct(Property $property, Equipment $equipment)
    {
        $this->property = $property;
        $this->equipment = $equipment;
    }

    public function getEquipment(): Equipment
    {
        return $this->equipment;
    }
}
