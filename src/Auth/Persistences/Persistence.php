<?php

namespace CubeSystems\Leaf\Auth\Persistences;

use Cartalyst\Sentinel\Persistences\EloquentPersistence;
use CubeSystems\Leaf\Auth\Users\User;

/**
 * Class Persistence
 * @package CubeSystems\Leaf\Auth\Persistences
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
