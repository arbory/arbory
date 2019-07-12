<?php

namespace Arbory\Base\Auth\Roles;

use Arbory\Base\Auth\Users\User;
use Cartalyst\Sentinel\Roles\EloquentRole;

/**
 * Class Role.
 */
class Role extends EloquentRole
{
    /**
     * @var string
     */
    protected $table = 'admin_roles';

    /**
     * @var string
     */
    protected static $usersModel = User::class;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The Users relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(static::$usersModel, 'admin_role_users', 'role_id', 'user_id')->withTimestamps();
    }
}
