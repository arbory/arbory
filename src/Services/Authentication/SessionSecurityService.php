<?php

namespace Arbory\Base\Services\Authentication;

use Arbory\Base\Support\Replies\FailureReply;
use Arbory\Base\Support\Replies\Reply;
use Arbory\Base\Support\Replies\SuccessReply;
use Cartalyst\Sentinel\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;

class SessionSecurityService implements SecurityStrategy
{
    /**
     * @var Sentinel
     */
    private $sentinel;

    /**
     * @param Sentinel $sentinel
     */
    public function __construct( Sentinel $sentinel )
    {
        $this->sentinel = $sentinel;
    }

    /**
     * @param UserInterface $user
     * @param bool $remember
     * @param bool $login
     * @return Reply
     */
    public function authenticateUser( UserInterface $user, bool $remember = false, bool $login = true ): Reply
    {
        return $this->authenticate( $user, $remember, $login );
    }
    
    /**
     * @param array $credentials
     * @param bool $remember
     * @param bool $login
     * @return Reply
     */
    public function authenticateWithCredentials( array $credentials, bool $remember = false, bool $login = true ): Reply
    {
        return $this->authenticate( $credentials, $remember, $login );
    }

    /**
     * @param \Cartalyst\Sentinel\Users\UserInterface|array  $credentials
     * @param boolean $remember
     * @param boolean $login
     * @return Reply
     */
    private function authenticate( $credentials, $remember = false, $login = true ): Reply
    {
        $user = $this->sentinel->authenticate( $credentials, $remember, $login );

        if( $user )
        {
            return new SuccessReply( trans( 'auth.success' ) );
        }

        return new FailureReply( trans( 'auth.failed' ) );
    }

    /**
     * @param UserInterface|null $user
     * @param boolean $everywhere
     * @return Reply
     */
    public function logout( UserInterface $user = null, $everywhere = false ): Reply
    {
        $this->sentinel->logout( $user, $everywhere );

        if( !$this->sentinel->check() )
        {
            return new SuccessReply( trans( 'auth.user_logout' ) );
        }

        return new FailureReply( trans( 'auth.generic_problem' ) );
    }
}
