<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\UserStatusRepository::class)]
class UserStatus
{
    use IdTrait;

    #[ORM\Column(length: 100, unique: true)]
    private string $label;

    /** @var Collection<int, User> */
    #[ORM\OneToMany(mappedBy: 'status', targetEntity: User::class)]
    private Collection $users;

    public function __construct(string $label = '')
    {
        $this->initializeId();
        $this->label = $label;
        $this->users = new ArrayCollection();
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

    /** @return Collection<int, User> */
    public function getUsers(): Collection
    {
        return $this->users;
    }
}
