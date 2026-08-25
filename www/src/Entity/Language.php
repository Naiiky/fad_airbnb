<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\LanguageRepository::class)]
class Language
{
    use IdTrait;

    #[ORM\Column(length: 100, unique: true)]
    private string $label;

    /** @var Collection<int, UserLanguage> */
    #[ORM\OneToMany(mappedBy: 'language', targetEntity: UserLanguage::class, orphanRemoval: true)]
    private Collection $userLanguages;

    public function __construct(string $label = '')
    {
        $this->initializeId();
        $this->label = $label;
        $this->userLanguages = new ArrayCollection();
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

    /** @return Collection<int, UserLanguage> */
    public function getUserLanguages(): Collection
    {
        return $this->userLanguages;
    }
}
