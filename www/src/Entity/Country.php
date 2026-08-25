<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\CountryRepository::class)]
class Country
{
    use IdTrait;

    #[ORM\Column(length: 100, unique: true)]
    private string $label;

    /** @var Collection<int, User> */
    #[ORM\OneToMany(mappedBy: 'country', targetEntity: User::class)]
    private Collection $users;

    /** @var Collection<int, Property> */
    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Property::class)]
    private Collection $properties;

    public function __construct(string $label = '')
    {
        $this->initializeId();
        $this->label = $label;
        $this->users = new ArrayCollection();
        $this->properties = new ArrayCollection();
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
