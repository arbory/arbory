<?php

namespace Arbory\Base\Services;

use Arbory\Base\Admin\Module;

/**
 * Class ModuleConfig.
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
    public function __construct(string $controllerClass)
    {
        $this->controllerClass = $controllerClass;
        $this->name = $this->createNameFromClass($controllerClass);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @param string $controllerClass
     * @return $this
     */
    public function setControllerClass($controllerClass)
    {
        $this->controllerClass = $controllerClass;

        return $this;
    }

    /**
     * @param string $authorizationType
     * @return $this
     */
    public function setAuthorizationType($authorizationType)
    {
        $this->authorizationType = $authorizationType;

        return $this;
    }

    /**
     * @param \array[] $authorizedRoles
     * @return $this
     */
    public function setAuthorizedRoles($authorizedRoles)
    {
        $this->authorizedRoles = $authorizedRoles;

        return $this;
    }

    /**
     * @param array $requiredPermissions
     * @return $this
     */
    public function setRequiredPermissions($requiredPermissions)
    {
        $this->requiredPermissions = $requiredPermissions;

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
     * @param $class
     * @return string
     */
    protected function createNameFromClass(string $class): string
    {
        if (! preg_match('#Controllers(\\\Admin)?\\\(?P<name>.*)Controller#ui', $class, $matches)) {
            return substr(md5($class), 0, 8);
        }

        $slug = str_replace('\\', '', $matches['name']);
        $slug = preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1 ', $slug);

        return str_slug($slug);
    }
}
