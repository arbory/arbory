<?php

namespace CubeSystems\Leaf\Console\Commands;

use Cartalyst\Sentinel\Sentinel;
use CubeSystems\Leaf\Providers\LeafServiceProvider;
use CubeSystems\Leaf\Services\StubRegistry;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;
use InvalidArgumentException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use LeafDatabaseSeeder;
use Illuminate\Console\DetectsApplicationNamespace;

/**
 * Class SeedCommand
 * @package CubeSystems\Leaf\Console\Commands
 */
class InstallCommand extends Command
{
    use ConfirmableTrait, DetectsApplicationNamespace;

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
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * @var StubRegistry
     */
    protected $stubRegistry;

    /**
     * @param Sentinel $sentinel
     * @param Filesystem $filesystem
     * @param DatabaseManager $databaseManager
     * @param StubRegistry $stubRegistry
     */
    public function __construct(
        Sentinel $sentinel,
        Filesystem $filesystem,
        DatabaseManager $databaseManager,
        StubRegistry $stubRegistry
    )
    {
        $this->sentinel = $sentinel;
        $this->filesystem = $filesystem;
        $this->databaseManager = $databaseManager;
        $this->stubRegistry = $stubRegistry;

        parent::__construct();
    }

    /**
     *
     */
    public function fire()
    {
        try
        {
            $this->databaseManager->connection();
        }
        catch( Exception $e )
        {
            $this->error( 'Unable to connect to the database.' );
            $this->error( 'Please fill valid database credentials into .env and run this command again.' );

            return;
        }

        $this->createDirectories();
        $this->seedAdminControllers();
        $this->publishConfig();
        $this->addWebpackTask();
        $this->runMigrations();
        $this->runSeeder();
        $this->publishLanguages();
        $this->createAdminUser();
        $this->npmDependencies();

        $this->info( 'Installation completed!' );
    }

    /**
     * @return void
     */
    protected function createDirectories()
    {
        $this->info( 'Creating directories' );

        $directories = [
            app_path( 'Http/Controllers/Admin' ),
            app_path( 'Pages' ),
            base_path( 'resources/views/admin' )
        ];

        foreach( $directories as $directory )
        {
            if( !$this->filesystem->isDirectory( $directory ) )
            {
                $this->filesystem->makeDirectory( $directory );
            }
        }
    }

    /**
     * @return void
     */
    protected function seedAdminControllers()
    {
        $directory = app_path( 'Http/Controllers/Admin/' );
        $controllers = [
            'UsersController',
            'RolesController',
            'NodesController'
        ];

        foreach( $controllers as $className )
        {
            $path = $directory . $className . '.php';

            if( !$this->filesystem->isFile( $path ) )
            {
                $content = $this->stubRegistry->make( 'extended_leaf_admin_controller', [
                    'namespaceRoot' => $this->getAppNamespace(),
                    'className' => $className,
                    'extendsClassName' => $className,
                ] );

                $this->filesystem->put( $path, $content );
            }
        }
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
    protected function publishLanguages()
    {
        $this->info( 'Publishing language resources' );

        $this->call( 'vendor:publish', [
            '--provider' => LeafServiceProvider::class,
            '--tag' => 'lang',
            '--force' => null,
        ] );

        $this->call( 'translator:load' );
        $this->call( 'translator:flush' );
    }

    /**
     *
     */
    protected function addWebpackTask()
    {
        $webpackConfig = 'webpack.mix.js';
        $leafRequire = "require('./vendor/cubesystems/leaf/webpack.mix')(mix);";

        try
        {
            $contents = $this->filesystem->get( $webpackConfig );

            if( strpos( $contents, $leafRequire ) === false )
            {
                $this->filesystem->append( $webpackConfig, PHP_EOL . $leafRequire );
            }
        }
        catch( FileNotFoundException $e )
        {
            $this->error( 'Webpack config not found' );
        }
    }

    /*
     *
     */
    protected function runSeeder()
    {
        $this->info( 'Running leaf database seeder' );
        $this->call( 'db:seed', [
            '--class' => LeafDatabaseSeeder::class
        ] );
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
