<?php

namespace Arbory\Base\Services\Authentication;

use Arbory\Base\Support\Replies\Reply;
use Cartalyst\Sentinel\Users\UserInterface;

interface SecurityStrategy
{
    /**
     * @param  bool  $remember
     * @param  bool  $login
     */
    public function authenticate(array $credentials, $remember = false, $login = true): Reply;

    /**
     * @param  UserInterface|null  $user
     * @param  bool  $everywhere
     */
    public function logout(UserInterface $user = null, $everywhere = false): Reply;
}
