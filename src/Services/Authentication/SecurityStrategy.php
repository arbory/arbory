<?php

namespace Arbory\Base\Services\Authentication;

use Arbory\Base\Support\Replies\Reply;
use Cartalyst\Sentinel\Users\UserInterface;

interface SecurityStrategy
{
    /**
     * @param array $credentials
     * @param bool $remember
     * @param bool $login
     * @return Reply
     */
    public function authenticate(array $credentials, $remember = false, $login = true): Reply;

    /**
     * @param UserInterface|null $user
     * @param bool $everywhere
     * @return Reply
     */
    public function logout(UserInterface $user = null, $everywhere = false): Reply;
}
