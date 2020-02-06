<?php

namespace Arbory\Base\Auth\Persistences;

use Arbory\Base\Auth\Users\User;
use Cartalyst\Sentinel\Persistences\EloquentPersistence;

/**
 * Class Persistence.
 */
class Persistence extends EloquentPersistence
{
    /**
     * @var string
     */
    protected $table = 'admin_persistences';

    /**
     * @var string
     */
    protected static $usersModel = User::class;
}
