<?php

namespace CubeSystems\Leaf\Services;

use Carbon\Carbon;
use Cartalyst\Sentinel\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;
use CubeSystems\Leaf\Services\AuthReply\ExceptionReply;
use CubeSystems\Leaf\Services\AuthReply\FailureReply;
use CubeSystems\Leaf\Services\AuthReply\Reply;
use CubeSystems\Leaf\Services\AuthReply\SuccessReply;
use Exception;
use InvalidArgumentException;

/**
 * Class AuthService
 * @package CubeSystems\Services
 */
class AuthService
{
    /**
     * @var Sentinel
     */
    private $sentinel;

    /**
     * @var mixed
     */
    private $activations;

    /**
     * @var mixed
     */
    private $reminders;

    /**
     * AuthService constructor.
     */
    public function __construct()
    {
        $this->sentinel = app()->make( 'sentinel' );

        $this->activations = app()->make( 'sentinel.activations' );
        $this->reminders = app()->make( 'sentinel.reminders' );
    }

    /**
     * @param  array $credentials
     * @param  boolean $remember
     * @param  boolean $login
     * @return Reply
     */
    public function authenticate( $credentials, $remember = false, $login = true )
    {
        try
        {
            $user = $this->sentinel->authenticate( $credentials, $remember, $login );
        }
        catch( Exception $e )
        {
            return $this->returnException( $e );
        }

        if( $user )
        {
            $message = request()->ajax() ?
                trans( 'leaf.session_initated', 'You have been authenticated.' ) : null;

            return new SuccessReply( $message );
        }

        $message = trans( 'failed_authorization', 'Access denied due to invalid credentials.' );

        return new FailureReply( $message );
    }

    /**
     * @param  UserInterface|null $user
     * @param  boolean $everywhere
     * @return Reply
     */
    public function logout( UserInterface $user = null, $everywhere = false )
    {
        try
        {
            $this->sentinel->logout( $user, $everywhere );
        }
        catch( Exception $e )
        {
            return $this->returnException( $e );
        }

        if( !$this->sentinel->check() )
        {
            $message = trans( 'user_logout', 'You have been logged out' );

            return new SuccessReply( $message );
        }

        $message = trans( 'generic_problem', 'There was a problem. Please contact a site administrator.' );

        return new FailureReply( $message );
    }

    /**
     * @param  array $credentials
     * @param  boolean $activation
     * @return Reply
     */
    public function register( array $credentials, $activation = false )
    {
        try
        {
            if( $this->sentinel->findUserByCredentials( $credentials ) )
            {
                throw new InvalidArgumentException( 'Invalid credentials provided' );
            }

            $user = $this->sentinel->register( $credentials, $activation );

            if( !$activation )
            {
                $activation = $this->activations->create( $user );
            }

            if( $user )
            {
                $result = new SuccessReply(
                    trans( 'registration_success', 'Registration complete' ),
                    [ 'user' => $user, 'activation' => $activation ]
                );
            }
            else
            {
                $result = new FailureReply(
                    trans( 'registration_failed', 'Registration denied due to invalid credentials.' )
                );
            }

            return $result;
        }
        catch( Exception $e )
        {
            $result = $this->returnException( $e );
        }

        return $result;
    }

    /**
     * @param  string $code
     * @return Reply
     */
    public function activate( $code )
    {
        try
        {
            // Attempt to fetch the user via the activation code
            $activation = $this->activations
                ->createModel()
                ->newQuery()
                ->where( 'code', $code )
                ->where( 'completed', false )
                ->where( 'created_at', '>', Carbon::now()->subSeconds( 259200 ) )
                ->first();

            if( !$activation )
            {
                $message = trans( 'activation_problem', 'Invalid or expired activation code.' );
                throw new InvalidArgumentException( $message );
            }
            $user = $this->sentinel->findUserById( $activation->user_id );

            // Complete the user's activation
            $this->activations->complete( $user, $code );

            // While we are here, lets remove any expired activations
            $this->activations->removeExpired();
        }
        catch( Exception $e )
        {
            return $this->returnException( $e );
        }

        if( $user )
        {
            $message = trans( 'activation_success', 'Activation successful.' );

            return new SuccessReply( $message );
        }

        $message = trans( 'activation_failed', 'There was a problem activating your account.' );

        return new FailureReply( $message );
    }

    /**
     * @param  string $code
     * @param  string $password
     * @return Reply
     */
    public function resetPassword( $code, $password )
    {
        try
        {
            // Attempt to fetch the user via the activation code
            $reminder = $this->reminders
                ->createModel()
                ->newQuery()
                ->where( 'code', $code )
                ->where( 'completed', false )
                ->where( 'created_at', '>', Carbon::now()->subSeconds( 259200 ) )
                ->first();

            if( !$reminder )
            {
                $message = trans( 'password_reset_problem', 'Invalid or expired password reset code; please request a new link.' );
                throw new InvalidArgumentException( $message );
            }
            $user = $this->sentinel->findUserById( $reminder->user_id );

            $this->reminders->complete( $user, $code, $password );
            $this->reminders->removeExpired();
        }
        catch( Exception $e )
        {
            return $this->returnException( $e );
        }

        if( $user )
        {
            $message = trans( 'password_reset_success', 'Password reset successful.' );

            return new SuccessReply( $message );
        }

        $message = trans( 'password_reset_failed', 'There was a problem resetting your password.' );

        return new FailureReply( $message );
    }

    /**
     * @param  Exception $e
     * @return ExceptionReply
     */
    protected function returnException( Exception $e )
    {
        $key = 'leaf.' . snake_case( class_basename( $e ) );
        $message = trans( $key, $e->getMessage() );

        return new ExceptionReply( $message, [], $e );
    }
}
