<?php

namespace Arbory\Base\Console\Commands;

use Arbory\Base\Providers\FileManagerServiceProvider;
use Arbory\Base\Providers\ArboryServiceProvider;
use Arbory\Base\Services\StubRegistry;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ArboryDatabaseSeeder;
use Illuminate\Console\DetectsApplicationNamespace;

/**
 * Class SeedCommand
 * @package Arbory\Base\Console\Commands
 */
class InstallCommand extends Command
{
    use ConfirmableTrait, DetectsApplicationNamespace;

    /**
     * @var string
     */
    protected $name = 'arbory:install';

    /**
     * @var string
     */
    protected $description = '';

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
     * @param Filesystem $filesystem
     * @param DatabaseManager $databaseManager
     * @param StubRegistry $stubRegistry
     */
    public function __construct(
        Filesystem $filesystem,
        DatabaseManager $databaseManager,
        StubRegistry $stubRegistry
    )
    {
        $this->filesystem = $filesystem;
        $this->databaseManager = $databaseManager;
        $this->stubRegistry = $stubRegistry;

        parent::__construct();
    }

    /**
     * @return void
     */
    public function handle()
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
        $this->publishFiles();
        $this->publishMixFile();
        $this->publishConfig();
        $this->runMigrations();
        $this->runSeeder();
        $this->publishFileManager();
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
            base_path( 'resources/views/admin' ),
            base_path( 'resources/views/controllers' )
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
    public function publishFiles()
    {
        $this->info( 'Publishing files' );

        $files = [
            base_path( 'routes/pages.php' ) => [ 'pages', [] ],
            resource_path( 'assets/js/admin.js' ) => [ 'admin.js', [] ],
        ];

        foreach( $files as $destination => list( $stub, $params ) )
        {
            if( !$this->filesystem->exists( $destination ) )
            {
                $content = $this->stubRegistry->make( $stub, $params );

                $this->filesystem->put( $destination, $content );
            }
        }
    }

    /**
     * @return void
     */
    protected function publishMixFile()
    {
        $webpackConfig = 'webpack.mix.js';
        $arboryRequire = "require('./vendor/arbory/arbory/webpack.mix')(mix);";

        try
        {
            $contents = $this->filesystem->get( $webpackConfig );

            if( strpos( $contents, $arboryRequire ) === false )
            {
                $content = $this->stubRegistry->make( 'webpack.mix.js', [] );

                $this->filesystem->put( base_path( 'webpack.mix.js' ), $content );
            }
        }
        catch( FileNotFoundException $e )
        {
            $this->error( 'Webpack config not found' );
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
                $content = $this->stubRegistry->make( 'extended_admin_controller', [
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
            '--provider' => ArboryServiceProvider::class,
            '--tag' => 'config',
        ] );
    }

    /**
     * @return void
     */
    protected function publishFileManager()
    {
        $this->info( 'Publishing file manager resources' );

        $this->call( 'vendor:publish', [
            '--provider' => FileManagerServiceProvider::class,
            '--force' => null,
        ] );
    }

    /**
     *
     */
    protected function publishLanguages()
    {
        $this->info( 'Publishing language resources' );

        $this->call( 'vendor:publish', [
            '--provider' => ArboryServiceProvider::class,
            '--tag' => 'lang',
            '--force' => null,
        ] );

        $this->call( 'translator:load' );
        $this->call( 'translator:flush' );
    }

    /*
     *
     */
    protected function runSeeder()
    {
        $this->info( 'Running Arbory database seeder' );
        $this->call( 'db:seed', [
            '--class' => ArboryDatabaseSeeder::class
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
        $this->call('arbory:create-user');
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
