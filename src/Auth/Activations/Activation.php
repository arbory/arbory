<?php

namespace Arbory\Base\Auth\Activations;

use Cartalyst\Sentinel\Activations\EloquentActivation;

/**
 * Class Activation.
 */
class Activation extends EloquentActivation
{
    /**
     * @var string
     */
    protected $table = 'admin_activations';
}
