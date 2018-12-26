<?php

namespace App\Security\Voter;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return $attribute === 'MANAGE'
            && $subject instanceof Article; // i will vote too
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Article $subject */

        /** @var User $user */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'MANAGE':
                // logic to determine if the user can EDIT
                // return true or false
                // check if the author of the article is the current logged in user

                if ($subject->getAuthor() === $user) {
                    return true;
                }

                if ($this->security->isGranted('ROLE_ADMIN_ARTICLE')) {
                    return true;
                }

                return false;

                break;
        }

        return false;
    }
}
