<?php

namespace App\Security;

use App\Entity\Property;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PropertyVoter extends Voter
{
    public const EDIT = 'PROPERTY_EDIT';
    public const PUBLISH = 'PROPERTY_PUBLISH';
    public const HIDE = 'PROPERTY_HIDE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::EDIT, self::PUBLISH, self::HIDE], true)
            && $subject instanceof Property;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (\in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        return $user instanceof User && $subject instanceof Property && $subject->getUser()->getId() === $user->getId();
    }
}
