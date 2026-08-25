<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\BookingStatusRepository::class)]
class BookingStatus
{
    use IdTrait;

    #[ORM\Column(length: 100, unique: true)]
    private string $label;

    /** @var Collection<int, Booking> */
    #[ORM\OneToMany(mappedBy: 'status', targetEntity: Booking::class)]
    private Collection $bookings;

    public function __construct(string $label = '')
    {
        $this->initializeId();
        $this->label = $label;
        $this->bookings = new ArrayCollection();
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
