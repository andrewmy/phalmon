<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exceptions\Unauthorized;
use App\Model\User;
use Dmkit\Phalcon\Auth\Middleware\Micro;
use Phalcon\Mvc\Controller;

/**
 * @property Micro $auth
 */
class SecurityController extends Controller
{
    /**
     * @throws Unauthorized
     */
    public function login(): array
    {
        $request = $this->request->getJsonRawBody(true);
        $username = $request['username'] ?? null;
        $password = $request['password'] ?? null;

        $user = null;
        if ($username && $password) {
            /** @var User $user */
            $user = User::findFirst(['conditions' => ['username' => $username]]);
        }
        if ($user) {
            if ($this->security->checkHash($password, $user->password)) {
                $token = $this->auth->make([
                    'sub' => (string) $user->getId(),
                    'username' => $user->username,
                    'iat' => \time(),
                ]);

                return ['token' => $token];
            }
        } else {
            // waste some time
            $this->security->hash(\mt_rand());
        }

        throw new Unauthorized('Bad credentials');
    }
}
