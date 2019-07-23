<?php

namespace Arbory\Base\Console;

use Arbory\Base\Console\Commands\SeedCommand;
use Arbory\Base\Console\Commands\RedirectHealthCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * @var array
     */
    protected $commands = [
        SeedCommand::class,
        RedirectHealthCommand::class,
    ];
}
