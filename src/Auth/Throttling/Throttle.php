<?php

namespace Arbory\Base\Auth\Throttling;

use Cartalyst\Sentinel\Throttling\EloquentThrottle;

/**
 * Class Throttle.
 */
class Throttle extends EloquentThrottle
{
    /**
     * @var string
     */
    protected $table = 'admin_throttle';
}
