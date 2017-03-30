<?php

namespace CubeSystems\Leaf\Auth\Users;

use Cartalyst\Sentinel\Users\EloquentUser;
use CubeSystems\Leaf\Auth\Activations\Activation;
use CubeSystems\Leaf\Auth\Persistences\Persistence;
use CubeSystems\Leaf\Auth\Reminders\Reminder;
use CubeSystems\Leaf\Auth\Roles\Role;
use CubeSystems\Leaf\Auth\Throttling\Throttle;

/**
 * Class User
 * @package CubeSystems\Leaf\Auth\Users
 */
class User extends EloquentUser
{
    /**
     * @var string
     */
    protected $table = 'admin_users';

    /**
     * @var string
     */
    protected static $rolesModel = Role::class;

    /**
     * {@inheritDoc}
     */
    protected static $persistencesModel = Persistence::class;

    /**
     * {@inheritDoc}
     */
    protected static $activationsModel = Activation::class;

    /**
     * {@inheritDoc}
     */
    protected static $remindersModel = Reminder::class;

    /**
     * {@inheritDoc}
     */
    protected static $throttlingModel = Throttle::class;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
