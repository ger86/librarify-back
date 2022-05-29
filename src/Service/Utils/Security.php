<?php

namespace App\Service\Utils;

use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Security as SymfonySecurity;

class Security
{

    public function __construct(private SymfonySecurity $symfonySecurity)
    {
    }

    public function getCurrentUser(): User
    {
        $user = $this->symfonySecurity->getUser();
        if (!$user instanceof User) {
            throw new LogicException('User should be logged in');
        }
        return $user;
    }
}
