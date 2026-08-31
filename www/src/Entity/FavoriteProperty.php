<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\FavoritePropertyRepository::class)]
class FavoriteProperty
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'favoriteProperties')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'favoriteProperties')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Property $property;

    public function __construct(User $user, Property $property)
    {
        $this->user = $user;
        $this->property = $property;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getProperty(): Property
    {
        return $this->property;
    }
}
