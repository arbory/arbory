<?php

namespace CubeSystems\Leaf\Users;

use Cartalyst\Sentinel\Users\EloquentUser;
use CubeSystems\Leaf\Roles\Role;

class User extends EloquentUser
{
    protected static $rolesModel = Role::class;

    public function __toString()
    {
        return $this->getFullName();
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
