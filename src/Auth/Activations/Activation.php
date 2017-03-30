<?php

namespace CubeSystems\Leaf\Auth\Activations;

use Cartalyst\Sentinel\Activations\EloquentActivation;

/**
 * Class Activation
 * @package CubeSystems\Leaf\Auth\Activations
 */
class Activation extends EloquentActivation
{
    /**
     * @var string
     */
    protected $table = 'admin_activations';
}
