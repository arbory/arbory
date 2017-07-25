<?php

namespace Arbory\Base\Auth\Persistences;

use Cartalyst\Sentinel\Persistences\EloquentPersistence;
use Arbory\Base\Auth\Users\User;

/**
 * Class Persistence
 * @package Arbory\Base\Auth\Persistences
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
