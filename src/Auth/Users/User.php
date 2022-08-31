<?php

namespace Arbory\Base\Auth\Users;

use Arbory\Base\Auth\Roles\Role;
use Arbory\Base\Auth\Reminders\Reminder;
use Arbory\Base\Auth\Throttling\Throttle;
use Cartalyst\Sentinel\Users\EloquentUser;
use Arbory\Base\Auth\Activations\Activation;
use Arbory\Base\Auth\Persistences\Persistence;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class User.
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
     * {@inheritdoc}
     */
    protected static $persistencesModel = Persistence::class;

    /**
     * {@inheritdoc}
     */
    protected static $activationsModel = Activation::class;

    /**
     * {@inheritdoc}
     */
    protected static $remindersModel = Reminder::class;

    /**
     * {@inheritdoc}
     */
    protected static $throttlingModel = Throttle::class;

    /**
     * @return string
     */
    public function __toString(): string
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
        return $this->getFirstName().' '.$this->getLastName();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(static::$rolesModel, 'admin_role_users', 'user_id', 'role_id')->withTimestamps();
    }
}
