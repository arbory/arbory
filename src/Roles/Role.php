<?php

namespace CubeSystems\Leaf\Roles;

use Cartalyst\Sentinel\Roles\EloquentRole;
use CubeSystems\Leaf\Users\User;

class Role extends EloquentRole
{
    protected static $usersModel = User::class;

    public function __toString()
    {
        return (string) $this->getName();
    }

    public function getName()
    {
        return $this->name;
    }
}
