<?php

namespace Arbory\Base\Auth\Throttling;

use Cartalyst\Sentinel\Throttling\EloquentThrottle;

/**
 * Class Throttle
 * @package Arbory\Base\Auth\Throttling
 */
class Throttle extends EloquentThrottle
{
    /**
     * @var string
     */
    protected $table = 'admin_throttle';
}
