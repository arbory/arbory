<?php

namespace CubeSystems\Leaf\Admin;

/**
 * Class ModuleComponent
 * @package CubeSystems\Leaf\Admin
 */
trait ModuleComponent
{
    /**
     * @var
     */
    protected $module;

    /**
     * @param Module $module
     * @return $this
     */
    public function setModule( Module $module )
    {
        $this->module = $module;

        return $this;
    }

    /**
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }
}
