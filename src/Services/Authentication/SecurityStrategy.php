<?php

namespace Arbory\Base\Services\Authentication;

use Arbory\Base\Support\Replies\Reply;
use Cartalyst\Sentinel\Users\UserInterface;

interface SecurityStrategy
{
    /**
     * @param UserInterface $user
     * @param bool $remember
     * @param bool $login
     * @return Reply
     */
    public function authenticateUser( UserInterface $user, bool $remember = false, bool $login = true ): Reply;
    
    /**
     * @param array $credentials
     * @param bool $remember
     * @param bool $login
     * @return Reply
     */
    public function authenticateWithCredentials( array $credentials, bool $remember = false, bool $login = true ): Reply;

    /**
     * @param UserInterface|null $user
     * @param bool $everywhere
     * @return Reply
     */
    public function logout( UserInterface $user = null, $everywhere = false ): Reply;
}