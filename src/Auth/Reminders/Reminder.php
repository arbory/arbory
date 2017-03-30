<?php

namespace CubeSystems\Leaf\Auth\Reminders;

use Cartalyst\Sentinel\Reminders\EloquentReminder;

/**
 * Class Reminder
 * @package CubeSystems\Leaf\Auth\Reminders
 */
class Reminder extends EloquentReminder
{
    /**
     * @var string
     */
    protected $table = 'admin_reminders';
}
