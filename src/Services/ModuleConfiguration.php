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
     * ModuleConfiguration constructor.
     * @param string $controllerClass
     */
    public function __construct( string $controllerClass )
    {
        $this->controllerClass = $controllerClass;
        $this->name = $this->createNameFromClass( $controllerClass);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @param string $name
     * @return $this
     */
//    public function setName( $name )
//    {
////        debug_print_backtrace(5,5);
////        dd( $name );
//        $this->name = $name;
//
//        return $this;
//    }

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
//    public function setMenuItemRoute( $menuItemRoute )
//    {
//        $this->menuItemRoute = $menuItemRoute;
//
//        return $this;
//    }

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

    protected function createNameFromClass( $class )
    {
        if( !preg_match( '#Controllers(\\\Admin)?\\\(?P<name>.*)Controller#ui', $class, $matches ) )
        {
            return substr( md5( $class ), 0, 8 );
        }

        $slug = str_replace( '\\', '', $matches['name'] );
        $slug = preg_replace( '/([a-zA-Z])(?=[A-Z])/', '$1 ', $slug );

        return str_slug( $slug );
    }

    /**
     * @param array $configurationArray
     */
//    private function configureFromArray( array $configurationArray )
//    {
////        $this->setName( $this->requireConfigurationValue( $configurationArray, 'name' ) );
//        $this->setControllerClass( $this->requireConfigurationValue( $configurationArray, 'controller_class' ) );
//
//        $this->setMenuItemRoute( array_get( $configurationArray, 'menu_item_route' ) );
//
//        $authorizationType = array_get( $configurationArray, 'authorization_type', Module::AUTHORIZATION_TYPE_NONE );
//        $this->setAuthorizationType( $authorizationType );
//
//        switch( $authorizationType )
//        {
//            case Module::AUTHORIZATION_TYPE_NONE;
//                break;
//            case Module::AUTHORIZATION_TYPE_ROLES:
//                $this->setAuthorizedRoles( $this->requireConfigurationValue( $configurationArray, 'authorized_roles' ) );
//                break;
//            case Module::AUTHORIZATION_TYPE_PERMISSIONS:
//                $this->setRequiredPermissions( $this->requireConfigurationValue( $configurationArray, 'required_permissions' ) );
//                break;
//            default:
//                throw new LogicException( 'Authorization type "' . $authorizationType . '" not recognized' );
//        }
//    }

    /**
     * @param array $configArray
     * @param string $name
     * @return mixed
     */
//    private function requireConfigurationValue( array $configArray, $name )
//    {
//        if( !array_has( $configArray, $name ) )
//        {
//            throw new LogicException( 'Missing "' . $name . '" in module configuration' );
//        }
//
//        return array_get( $configArray, $name );
//    }
}
