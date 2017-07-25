<?php

namespace Arbory\Base\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Routing\Router
 */
class ArboryRouter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'arbory_router';
    }
}
