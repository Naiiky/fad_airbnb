<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\EquipmentRepository::class)]
class Equipment
{
    use IdTrait;

    #[ORM\Column(length: 100, unique: true)]
    private string $label;

    /** @var Collection<int, PropertyEquipment> */
    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: PropertyEquipment::class, orphanRemoval: true)]
    private Collection $propertyEquipments;

    public function __construct(string $label = '')
    {
        $this->initializeId();
        $this->label = $label;
        $this->propertyEquipments = new ArrayCollection();
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
