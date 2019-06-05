<?php

namespace Arbory\Base\Console\Commands;

use Exception;
use ArboryDatabaseSeeder;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\DatabaseManager;
use Arbory\Base\Providers\ArboryServiceProvider;
use Illuminate\Console\DetectsApplicationNamespace;
use Arbory\Base\Providers\FileManagerServiceProvider;

/**
 * Class SeedCommand.
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
     * @param Filesystem $filesystem
     * @param DatabaseManager $databaseManager
     */
    public function __construct(
        Filesystem $filesystem,
        DatabaseManager $databaseManager
    ) {
        $this->filesystem = $filesystem;
        $this->databaseManager = $databaseManager;

        parent::__construct();
    }

    public function handle()
    {
        try {
            $this->databaseManager->connection();
        } catch (Exception $e) {
            $this->error('Unable to connect to the database.');
            $this->error('Please fill valid database credentials into .env and run this command again.');

            return;
        }

        $this->publishConfig();
        $this->runMigrations();
        $this->runSeeder();
        $this->publishFileManager();
        $this->publishLanguages();
        $this->createAdminUser();

        $this->info('Installation completed!');
    }

    protected function publishConfig()
    {
        $this->info('Publishing configuration file');
        $this->call('vendor:publish', [
            '--provider' => ArboryServiceProvider::class,
            '--tag' => 'config',
        ]);
    }

    protected function publishFileManager()
    {
        $this->info('Publishing file manager resources');

        $this->call('vendor:publish', [
            '--provider' => FileManagerServiceProvider::class,
            '--force' => null,
        ]);
    }

    protected function publishLanguages()
    {
        $this->info('Publishing language resources');

        $this->call('vendor:publish', [
            '--provider' => ArboryServiceProvider::class,
            '--tag' => 'lang',
            '--force' => null,
        ]);

        $this->call('translator:load');
        $this->call('translator:flush');
    }

    protected function runSeeder()
    {
        $this->info('Running Arbory database seeder');
        $this->call('db:seed', [
            '--class' => ArboryDatabaseSeeder::class,
        ]);
    }

    protected function runMigrations()
    {
        $this->info('Running migrations');
        $this->call('migrate');
    }

    protected function createAdminUser()
    {
        $this->call('arbory:create-user');
    }
}
