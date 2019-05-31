<?php

namespace Arbory\Base\Auth\Users;

use Cartalyst\Sentinel\Users\EloquentUser;
use Arbory\Base\Auth\Activations\Activation;
use Arbory\Base\Auth\Persistences\Persistence;
use Arbory\Base\Auth\Reminders\Reminder;
use Arbory\Base\Auth\Roles\Role;
use Arbory\Base\Auth\Throttling\Throttle;

/**
 * Class User
 * @package Arbory\Base\Auth\Users
 */
class User extends EloquentUser
{
    public const PROVIDER = 'database';
    
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
     * User constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->fillable[] = 'provider';
        $this->loginNames[] = 'provider';
    }
    
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany( static::$rolesModel, 'admin_role_users', 'user_id', 'role_id' )->withTimestamps();
    }

}
