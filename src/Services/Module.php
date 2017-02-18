<?php

namespace CubeSystems\Leaf\Services;

use Cartalyst\Sentinel\Sentinel;
use \LogicException;

/**
 * Class Module
 * @package CubeSystems\Leaf\Services
 */
class Module
{
    const AUTHORIZATION_TYPE_ROLES = 'roles';
    const AUTHORIZATION_TYPE_PERMISSIONS = 'permissions';
    const AUTHORIZATION_TYPE_NONE = 'none';

    /**
     * @var ModuleConfiguration
     */
    private $configuration;

    /**
     * @param ModuleConfiguration $configuration
     */
    public function __construct( ModuleConfiguration $configuration )
    {
        $this->configuration = $configuration;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->configuration->getName();
    }

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return $this->configuration->getControllerClass();
    }

    /**
     * @param Sentinel $sentinel
     * @return bool
     */
    public function isAuthorized( Sentinel $sentinel )
    {
        $authorizationType = $this->configuration->getAuthorizationType();

        switch( $authorizationType )
        {
            case Module::AUTHORIZATION_TYPE_NONE:

                $result = true;
                break;

            case Module::AUTHORIZATION_TYPE_ROLES:

                $result = false;

                foreach( $this->configuration->getAuthorizedRoles() as $authorizedRole )
                {
                    /** @noinspection PhpUndefinedMethodInspection */
                    if( $sentinel->inRole( $authorizedRole ) )
                    {
                        $result = true;
                        break;
                    }
                }

                break;

            default:
                throw new LogicException( 'Authorization type "' . $authorizationType . '" is not recognized' );
        }

        return $result;
    }
}