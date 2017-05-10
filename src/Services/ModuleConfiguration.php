<?php

namespace CubeSystems\Leaf\Services;

use Symfony\Component\Console\Exception\LogicException;

/**
 * Class ModuleConfig
 * @package CubeSystems\Leaf\Services
 */
class ModuleConfiguration
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $controllerClass;

    /**
     * @var string
     */
    protected $authorizationType = Module::AUTHORIZATION_TYPE_NONE;

    /**
     * @var array[]
     */
    protected $authorizedRoles = [];

    /**
     * @var array
     */
    protected $requiredPermissions = [];

    /**
     * @var string
     */
    protected $menuItemRoute;

    /**
     * ModuleConfig constructor.
     * @param array|null $configurationArray
     */
    public function __construct( array $configurationArray = null )
    {
        if( $configurationArray && is_array( $configurationArray ) )
        {
            $this->configureFromArray( $configurationArray );
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return class_basename( $this->controllerClass );
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $controllerClass
     * @return $this
     */
    public function setControllerClass( $controllerClass )
    {
        $this->controllerClass = $controllerClass;

        return $this;
    }

    /**
     * @param string $authorizationType
     * @return $this
     */
    public function setAuthorizationType( $authorizationType )
    {
        $this->authorizationType = $authorizationType;

        return $this;
    }

    /**
     * @param \array[] $authorizedRoles
     * @return $this
     */
    public function setAuthorizedRoles( $authorizedRoles )
    {
        $this->authorizedRoles = $authorizedRoles;

        return $this;
    }

    /**
     * @param array $requiredPermissions
     * @return $this
     */
    public function setRequiredPermissions( $requiredPermissions )
    {
        $this->requiredPermissions = $requiredPermissions;

        return $this;
    }

    /**
     * @param string $menuItemRoute
     * @return $this
     */
    public function setMenuItemRoute( $menuItemRoute )
    {
        $this->menuItemRoute = $menuItemRoute;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return $this->controllerClass;
    }

    /**
     * @return string
     */
    public function getAuthorizationType()
    {
        return $this->authorizationType;
    }

    /**
     * @return \array[]
     */
    public function getAuthorizedRoles()
    {
        return $this->authorizedRoles;
    }

    /**
     * @return array
     */
    public function getRequiredPermissions()
    {
        return $this->requiredPermissions;
    }

    /**
     * @param array $configurationArray
     */
    private function configureFromArray( array $configurationArray )
    {
        $this->setName( $this->requireConfigurationValue( $configurationArray, 'name' ) );
        $this->setControllerClass( $this->requireConfigurationValue( $configurationArray, 'controller_class' ) );

        $this->setMenuItemRoute( array_get( $configurationArray, 'menu_item_route' ) );

        $authorizationType = array_get( $configurationArray, 'authorization_type', Module::AUTHORIZATION_TYPE_NONE );
        $this->setAuthorizationType( $authorizationType );

        switch( $authorizationType )
        {
            case Module::AUTHORIZATION_TYPE_NONE;
                break;
            case Module::AUTHORIZATION_TYPE_ROLES:
                $this->setAuthorizedRoles( $this->requireConfigurationValue( $configurationArray, 'authorized_roles' ) );
                break;
            case Module::AUTHORIZATION_TYPE_PERMISSIONS:
                $this->setRequiredPermissions( $this->requireConfigurationValue( $configurationArray, 'required_permissions' ) );
                break;
            default:
                throw new LogicException( 'Authorization type "' . $authorizationType . '" not recognized' );
        }
    }

    /**
     * @param array $configArray
     * @param string $name
     * @return mixed
     */
    private function requireConfigurationValue( array $configArray, $name )
    {
        if( !array_has( $configArray, $name ) )
        {
            throw new LogicException( 'Missing "' . $name . '" in module configuration' );
        }

        return array_get( $configArray, $name );
    }
}
