<?php

namespace Arbory\Base\Admin\Module;

use Arbory\Base\Admin\Module;

/**
 * Class ResourceRoutes.
 */
class ResourceRoutes
{
    /**
     * ResourceRoutes constructor.
     */
    public function __construct(protected Module $module)
    {
    }

    /**
     * @param $name
     * @param  array  $parameters
     * @return string
     */
    public function getUrl($name, $parameters = [])
    {
        return route('admin.'.$this->module->name().'.'.$name, $parameters);
    }
}
