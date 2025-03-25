<?php

namespace App\Service;

use Symfony\Component\Security\Core\Security;
use App\Entity\User;

class UserService
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getUserData(): ?User
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }
}
