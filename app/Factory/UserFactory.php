<?php

declare(strict_types=1);

namespace App\Factory;

use App\Model\User;
use Phalcon\Security;

class UserFactory
{
    /** @var Security */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function create(string $username, string $password): User
    {
        $user = new User();
        $user->username = $username;
        $user->password = $this->security->hash($password, 13);

        return $user;
    }
}
