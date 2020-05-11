<?php

namespace Arbory\Base\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Translation\Translator;
use Illuminate\Support\ServiceProvider;
use Waavi\Translation\Loaders\FileLoader;
use Waavi\Translation\Loaders\CacheLoader;
use Waavi\Translation\Loaders\MixedLoader;
use Waavi\Translation\Loaders\DatabaseLoader;
use Waavi\Translation\Cache\RepositoryFactory;
use Waavi\Translation\Repositories\LanguageRepository;
use Waavi\Translation\Repositories\TranslationRepository;
use Arbory\Base\Console\Commands\TranslationsLoaderCommand;
use Illuminate\Translation\FileLoader as LaravelFileLoader;
use Arbory\Base\Console\Commands\TranslationsCacheFlushCommand;

/**
 * Class TranslationServiceProvider.
 */
class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCacheRepository();
        $this->registerLoader();

        $this->app->singleton('translator', function (Application $app) {
            $trans = new Translator($app['translation.loader'], $app->getLocale());
            $trans->setFallback(config('app.fallback_locale'));

            return $trans;
        });

        $this->registerFileLoader();
        $this->registerCacheFlusher();

        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'arbory');
    }

    /**
     *  IOC alias provided by this Service Provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['translation.cache.repository', 'translation.loader'];
    }

    /**
     *  Register the translation cache repository.
     *
     * @return void
     */
    public function registerCacheRepository()
    {
        $this->app->singleton('translation.cache.repository', function ($app) {
            return RepositoryFactory::make(
                $app['cache']->getStore(),
                'translations'
            );
        });
    }

    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function (Application $app) {
            $defaultLocale = $app->getLocale();
            $cacheTimeout = config('translator.cache.timeout', 60);

            $laravelFileLoader = new LaravelFileLoader($app['files'], base_path('/resources/lang'));
            $fileLoader = new FileLoader($defaultLocale, $laravelFileLoader);
            $databaseLoader = new DatabaseLoader($defaultLocale, $app->make(TranslationRepository::class));
            $loader = new MixedLoader($defaultLocale, $databaseLoader, $fileLoader);

            return new CacheLoader($defaultLocale, $app['translation.cache.repository'], $loader, $cacheTimeout);
        });
    }

    /**
     * Register the translator:load language file loader.
     *
     * @return void
     */
    protected function registerFileLoader()
    {
        $app = $this->app;
        $defaultLocale = config('app.locale');
        $languageRepository = $app->make(LanguageRepository::class);
        $translationRepository = $app->make(TranslationRepository::class);
        $translationsPath = base_path('resources/lang');
        $command = new TranslationsLoaderCommand(
            $languageRepository,
            $translationRepository,
            $app['files'],
            $translationsPath,
            $defaultLocale
        );

        $this->app['command.translator:load'] = $command;
        $this->commands('command.translator:load');
    }

    /**
     *  Flushes the translation cache.
     *
     * @return void
     */
    public function registerCacheFlusher()
    {
        $command = new TranslationsCacheFlushCommand($this->app['translation.cache.repository'], true);

        $this->app['command.translator:flush'] = $command;
        $this->commands('command.translator:flush');
    }
}
