<?php

namespace Arbory\Base\Auth\Reminders;

use Cartalyst\Sentinel\Reminders\EloquentReminder;

/**
 * Class Reminder.
 */
class Reminder extends EloquentReminder
{
    /**
     * @var string
     */
    protected $table = 'admin_reminders';
}
