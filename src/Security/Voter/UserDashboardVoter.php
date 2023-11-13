<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserDashboardVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $expectedUser = $subject;

        /** @var User $user */
        $user = $token->getUser();

        if(in_array('ROLE_ADMIN', $user->getRoles()))
        {
            return true;
        }

        if($expectedUser !== $user)
        {
            return false;
        }


        return true;
    }
}
