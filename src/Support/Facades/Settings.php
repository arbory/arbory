<?php

namespace Arbory\Base\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Arbory\Base\Admin\Settings\Settings
 */
class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'arbory_settings';
    }
}
