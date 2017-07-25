<?php

namespace Arbory\Base\Auth\Activations;

use Cartalyst\Sentinel\Activations\EloquentActivation;

/**
 * Class Activation
 * @package Arbory\Base\Auth\Activations
 */
class Activation extends EloquentActivation
{
    /**
     * @var string
     */
    protected $table = 'admin_activations';
}
