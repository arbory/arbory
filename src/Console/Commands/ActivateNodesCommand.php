<?php

namespace Arbory\Base\Console\Commands;

use Arbory\Base\Nodes\Node;
use Illuminate\Console\Command;

/**
 * Class ActivateNodesCommand
 * @package App\Console\Commands
 */
class ActivateNodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arbory:activate-nodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate nodes that have passed their activation date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Node::query()
            ->where( 'active', 0 )
            ->where( 'activate_at', '<=', date( 'Y-m-d H:i' ) )
            ->update( [ 'active' => 1 ] );
    }
}