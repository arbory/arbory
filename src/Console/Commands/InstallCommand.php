<?php

namespace CubeSystems\Leaf\Console\Commands;

use Cartalyst\Sentinel\Sentinel;
use CubeSystems\Leaf\Providers\LeafServiceProvider;
use DB;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use InvalidArgumentException;
use LeafDatabaseSeeder;


/**
 * Class SeedCommand
 * @package CubeSystems\Leaf\Console\Commands
 */
class InstallCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $name = 'leaf:install';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * InstallCommand constructor.
     * @param Sentinel $sentinel
     */
    public function __construct( Sentinel $sentinel )
    {
        $this->sentinel = $sentinel;

        parent::__construct();
    }

    /**
     *
     */
    public function fire()
    {
        try
        {
            DB::connection();
        }
        catch( Exception $e )
        {
            $this->error( 'Unable to connect to the database.' );
            $this->error( 'Please fill valid database credentials into .env and run this command again.' );

            return;
        }

        $this->publishConfig();
        $this->publishAssets();
        $this->addWebpackTask();
        $this->runMigrations();
        $this->runSeeds();
        $this->createAdminUser();
        $this->npmDependencies();

        $this->info( 'Installation completed!' );

    }

    /**
     *
     */
    protected function publishAssets()
    {
        $this->info( 'Publishing Leaf assets' );
        $this->call( 'vendor:publish', [
            '--provider' => LeafServiceProvider::class,
            '--tag' => 'assets',
        ] );
    }

    /**
     *
     */
    protected function publishConfig()
    {
        $this->info( 'Publishing configuration file' );
        $this->call( 'vendor:publish', [
            '--provider' => LeafServiceProvider::class,
            '--tag' => 'config',
        ] );
    }

    /**
     *
     */
    protected function addWebpackTask()
    {
        $webpackConfig = base_path( 'webpack.mix.js' );

        if( \File::exists( $webpackConfig ) )
        {
            \File::append( $webpackConfig, "\nrequire('./webpack.leaf')(mix);" );
        }
    }

    /**
     *
     */
    protected function runMigrations()
    {
        $this->info( 'Running migrations' );
        $this->call( 'migrate' );
    }

    /**
     *
     */
    protected function runSeeds()
    {
        $this->info( 'Seeding the database' );
        $this->call( 'db:seed', [
            '--class' => LeafDatabaseSeeder::class,
        ] );
    }

    /**
     *
     */
    protected function createAdminUser()
    {
        $this->info( 'Let\'s create admin user' );

        $users = $this->sentinel->getUserRepository();
        $activations = $this->sentinel->getActivationRepository();
        $roles = $this->sentinel->getRoleRepository();

        $user = null;

        while( $user === null )
        {
            $email = $this->ask( 'Admin email' );
            $password = $this->secret( 'What is the password?' );

            try
            {
                $user = $users->create( [
                    'email' => $email,
                    'password' => $password
                ] );

                $activation = $activations->create( $user );
                $activations->complete( $user, $activation->getCode() );

                break;
            }
            catch( InvalidArgumentException $exception )
            {
                $this->error( $exception->getMessage() );
            }
        }

        $administratorRole = $roles->create( [
            'name' => 'Administrator',
            'slug' => 'administrator',
            'permissions' => [
                'users.create' => true,
                'users.update' => true,
                'users.view' => true,
                'users.destroy' => true,
                'roles.create' => true,
                'roles.update' => true,
                'roles.view' => true,
                'roles.delete' => true
            ]
        ] );

        $administratorRole->users()->attach( $user );
    }

    protected function npmDependencies()
    {
        if( `which npm` )
        {
            shell_exec('npm install --silent');
            shell_exec('npm run dev');
        }
    }
}
