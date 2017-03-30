<?php

namespace CubeSystems\Leaf\Console\Commands;

use Cartalyst\Sentinel\Sentinel;
use CubeSystems\Leaf\Providers\LeafServiceProvider;
use DB;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use InvalidArgumentException;


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
        $this->addWebpackTask();
        $this->runMigrations();
        $this->createAdminUser();
        $this->npmDependencies();

        $this->info( 'Installation completed!' );
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

        $leafRequire = "require('./vendor/cubesystems/leaf/webpack.mix')(mix);";

        if( !\File::exists( $webpackConfig ) )
        {
            $this->error( "Webpack config not found" );

            return;
        }

        if( strpos( \File::get( $webpackConfig ), $leafRequire ) === false )
        {
            \File::append( $webpackConfig, "\n" . $leafRequire );
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
    protected function createAdminUser()
    {
        $users = $this->sentinel->getUserRepository();
        $activations = $this->sentinel->getActivationRepository();
        $roles = $this->sentinel->getRoleRepository();

        if( $users->all()->count() > 0 )
        {
            $this->info( 'Admin user already exists' );

            return;
        }

        $this->info( 'Let\'s create admin user' );

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

    /**
     *
     */
    protected function npmDependencies()
    {
        if( !`which npm` )
        {
            $this->comment( 'NPM not found. To complete the installation, run "npm install" and "npm run dev" manually.' );

            return;
        }

        $this->info( 'Installing NPM packages' );
        shell_exec( 'npm install --silent' );

        $this->info( 'Compiling assets' );
        shell_exec( 'npm run dev' );
    }
}
