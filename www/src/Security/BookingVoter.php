<?php

namespace App\Security;

use App\Entity\Booking;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookingVoter extends Voter
{
    public const VIEW = 'BOOKING_VIEW';
    public const HOST_MANAGE = 'BOOKING_HOST_MANAGE';
    public const CANCEL = 'BOOKING_CANCEL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::HOST_MANAGE, self::CANCEL], true)
            && $subject instanceof Booking;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface || !$subject instanceof Booking) {
            return false;
        }

        if (\in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        if (!$user instanceof User) {
            return false;
        }

        $isTraveler = $subject->getUser()->getId() === $user->getId();
        $isHost = $subject->getProperty()->getUser()->getId() === $user->getId();

        return match ($attribute) {
            self::VIEW => $isTraveler || $isHost,
            self::HOST_MANAGE => $isHost,
            self::CANCEL => $isTraveler || $isHost,
            default => false,
        };
    }
}
