<?php

namespace Arbory\Base\Services\Authentication;

use Cartalyst\Sentinel\Users\UserInterface;

class Authentication
{
    public function __construct(private AuthenticationMethod $driver)
    {
    }

    /**
     * @param  bool  $remember
     * @param  bool  $login
     */
    public function authenticate(array $credentials, $remember = false, $login = true): bool
    {
        return $this->driver->authenticate($credentials, $remember, $login);
    }

    /**
     * @param  UserInterface|null  $user
     * @param  bool  $everywhere
     */
    public function logout(UserInterface $user = null, $everywhere = false): bool
    {
        return $this->driver->logout($user, $everywhere);
    }
}
