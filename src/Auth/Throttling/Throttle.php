<?php

namespace CubeSystems\Leaf\Auth\Throttling;

use Cartalyst\Sentinel\Throttling\EloquentThrottle;

/**
 * Class Throttle
 * @package CubeSystems\Leaf\Auth\Throttling
 */
class Throttle extends EloquentThrottle
{
    /**
     * @var string
     */
    protected $table = 'admin_throttle';
}
