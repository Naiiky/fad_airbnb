<?php

namespace App\Security;

class AgeMajorityChecker
{
    public function isAdult(\DateTimeImmutable $birthDate, ?\DateTimeImmutable $today = null): bool
    {
        $today ??= new \DateTimeImmutable('today');

        return $birthDate->modify('+18 years') <= $today;
    }
}
