<?php

namespace CubeSystems\Leaf\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\ConnectionResolverInterface as Resolver;

/**
 * Class SeedCommand
 * @package CubeSystems\Leaf\Console\Commands
 */
class SeedCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $name = 'leaf:seed';

    /**
     * @var string
     */
    protected $description = 'Seed the the database with with initial records for Leaf framework';

    /**
     * @var ConnectionResolverInterface
     */
    protected $resolver;

    /**
     * @param  ConnectionResolverInterface $resolver
     */
    public function __construct( Resolver $resolver )
    {
        parent::__construct();

        $this->resolver = $resolver;
    }

    /**
     *
     */
    public function fire()
    {
        if( !$this->confirmToProceed() )
        {
            return;
        }

        $this->resolver->setDefaultConnection( $this->getDatabase() );

        Model::unguarded( function ()
        {
            $this->getSeeder()->run();
        } );
    }

    /**
     * @return Seeder
     */
    protected function getSeeder()
    {
        $class = $this->laravel->make( $this->input->getOption( 'class' ) );

        return $class->setContainer( $this->laravel )->setCommand( $this );
    }

    /**
     * @return string
     */
    protected function getDatabase()
    {
        $database = $this->input->getOption( 'database' );

        return $database ?: $this->laravel['config']['database.default'];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            [ 'class', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder', 'LeafDatabaseSeeder' ],

            [ 'database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed' ],

            [ 'force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.' ],
        ];
    }
}
