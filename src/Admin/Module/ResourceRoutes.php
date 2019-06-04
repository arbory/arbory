<?php

namespace Arbory\Base\Admin\Module;

use Arbory\Base\Admin\Module;

/**
 * Class ResourceRoutes.
 */
class ResourceRoutes
{
    /**
     * @var Module
     */
    protected $module;

    /**
     * ResourceRoutes constructor.
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * @param $name
     * @param array $parameters
     * @return string
     */
    public function getUrl($name, $parameters = [])
    {
        return route(config('arbory.uri').'.'.$this->module->name().'.'.$name, $parameters);
    }
}
