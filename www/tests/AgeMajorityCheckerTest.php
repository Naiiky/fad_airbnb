<?php

namespace App\Tests;

use App\Security\AgeMajorityChecker;
use PHPUnit\Framework\TestCase;

class AgeMajorityCheckerTest extends TestCase
{
    public function testUserIsAdultOnEighteenthBirthday(): void
    {
        $checker = new AgeMajorityChecker();

        self::assertTrue($checker->isAdult(
            new \DateTimeImmutable('2008-08-26'),
            new \DateTimeImmutable('2026-08-26'),
        ));
    }

    public function testUserIsNotAdultTheDayBeforeEighteenthBirthday(): void
    {
        $checker = new AgeMajorityChecker();

        self::assertFalse($checker->isAdult(
            new \DateTimeImmutable('2008-08-27'),
            new \DateTimeImmutable('2026-08-26'),
        ));
    }
}
