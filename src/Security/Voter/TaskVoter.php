<?php

namespace App\Security\Voter;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                    if($subject->getUser()== $user){
                        return true;
                    }
                break;
            case 'DELETE':
                if($subject->getUser()== $user || ($subject->getUser()->getUsername()== 'anonyme' && in_array("ROLE_ADMIN",$user->getRoles()) )){
                    return true;
                }
                break;
        }

        return false;
    }
}
