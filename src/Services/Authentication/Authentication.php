<?php

namespace Arbory\Base\Services\Authentication;

use Cartalyst\Sentinel\Users\UserInterface;

class Authentication
{
    /**
     * @var AuthenticationMethod
     */
    private $driver;

    /**
     * @param AuthenticationMethod $driver
     */
    public function __construct(AuthenticationMethod $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param array $credentials
     * @param bool $remember
     * @param bool $login
     * @return bool
     */
    public function authenticate(array $credentials, $remember = false, $login = true): bool
    {
        return $this->driver->authenticate($credentials, $remember, $login);
    }

    /**
     * @param UserInterface|null $user
     * @param bool $everywhere
     * @return bool
     */
    public function logout(UserInterface $user = null, $everywhere = false): bool
    {
        return $this->driver->logout($user, $everywhere);
    }
}
