<?php

namespace CubeSystems\Leaf\Console;

use CubeSystems\Leaf\Console\Commands\SeedCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package CubeSystems\Leaf\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * @var array
     */
    protected $commands = [
        SeedCommand::class
    ];
}
