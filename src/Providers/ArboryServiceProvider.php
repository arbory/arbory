<?php namespace Arbory\Base\Providers;

use Arbory\Base\Admin\Admin;
use Arbory\Base\Admin\Form\Fields\Checkbox;
use Arbory\Base\Admin\Form\Fields\DateTime;
use Arbory\Base\Admin\Form\Fields\HasMany;
use Arbory\Base\Admin\Form\Fields\Hidden;
use Arbory\Base\Admin\Form\Fields\ArboryFile;
use Arbory\Base\Admin\Form\Fields\Link;
use Arbory\Base\Admin\Form\Fields\Richtext;
use Arbory\Base\Admin\Form\Fields\Text;
use Arbory\Base\Admin\Form\Fields\Textarea;
use Arbory\Base\Admin\Form\Fields\Translatable;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Console\Commands\GenerateCommand;
use Arbory\Base\Console\Commands\GeneratorCommand;
use Arbory\Base\Console\Commands\InstallCommand;
use Arbory\Base\Console\Commands\SeedCommand;
use Arbory\Base\Files\ArboryImage;
use Arbory\Base\Http\Middleware\ArboryAdminAuthMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminGuestMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminHasAccessMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminHasAllowedIpMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminInRoleMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminModuleAccessMiddleware;
use Arbory\Base\Http\Middleware\ArboryRouteRedirectMiddleware;
use Arbory\Base\Menu\Menu;
use Arbory\Base\Services\AssetPipeline;
use Arbory\Base\Services\Authentication\SessionSecurityService;
use Arbory\Base\Services\Authentication\SecurityStrategy;
use Arbory\Base\Services\FieldTypeRegistry;
use Arbory\Base\Services\StubRegistry;
use Arbory\Base\Views\LayoutViewComposer;
use Dimsav\Translatable\TranslatableServiceProvider;
use File;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\ExcelServiceProvider;
use Roboc\Glide\GlideImageServiceProvider;
use Route;

/**
 * Class ArboryServiceProvider
 * @package Arbory\Base
 */
class ArboryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerResources();
        $this->registerServiceProviders();
        $this->registerAliases();
        $this->registerModuleRegistry();
        $this->registerCommands();
        $this->registerRoutesAndMiddlewares();
        $this->registerFields();
        $this->registerGeneratorStubs();
        $this->registerLocales();
        $this->registerViewComposers();
        $this->registerValidationRules();
        $this->registerAssets();

        $this->app->singleton( SecurityStrategy::class, function()
        {
            return $this->app->make( SessionSecurityService::class );
        } );

        $this->loadTranslationsFrom( __DIR__ . '/resources/lang', 'arbory' );
    }

    /**
     * Register related service providers
     */
    private function registerServiceProviders()
    {
        $this->app->register( ArboryTranslationServiceProvider::class );
        $this->app->register( TranslatableServiceProvider::class );
        $this->app->register( ArboryFileServiceProvider::class );
        $this->app->register( ArboryAuthServiceProvider::class );
        $this->app->register( GlideImageServiceProvider::class );
        $this->app->register( AssetServiceProvider::class );
        $this->app->register( SettingsServiceProvider::class );
        $this->app->register( ExcelServiceProvider::class );
        $this->app->register( FileManagerServiceProvider::class );
    }

    /**
     * Register related aliases
     */
    private function registerAliases()
    {
        $aliasLoader = AliasLoader::getInstance();
//        $aliasLoader->alias( 'TranslationCache', \Waavi\Translation\Facades\TranslationCache::class );
        $aliasLoader->alias( 'Activation', \Cartalyst\Sentinel\Laravel\Facades\Activation::class );
        $aliasLoader->alias( 'Reminder', \Cartalyst\Sentinel\Laravel\Facades\Reminder::class );
        $aliasLoader->alias( 'Sentinel', \Cartalyst\Sentinel\Laravel\Facades\Sentinel::class );
        $aliasLoader->alias( 'GlideImage', \Roboc\Glide\Support\Facades\GlideImage::class );
        $aliasLoader->alias( 'Excel', \Maatwebsite\Excel\Facades\Excel::class );
    }

    /**
     * Publish configuration file.
     */
    private function registerResources()
    {
        $configFilename = __DIR__ . '/../../config/arbory.php';

        $this->mergeConfigFrom( $configFilename, 'arbory' );

        $this->publishes( [
            $configFilename => config_path( 'arbory.php' )
        ], 'config' );

        $this->publishes( [
            __DIR__ . '/../../stubs/settings.stub' => config_path( 'settings.php' )
        ], 'config' );

        $this->publishes( [
            __DIR__ . '/../../stubs/admin_routes.stub' => base_path( '/routes/admin.php' )
        ], 'config' );

        $this->publishes([
            __DIR__ . '/../../resources/lang/' => base_path('resources/lang/vendor/arbory')
        ], 'lang');

        $this->loadMigrationsFrom( __DIR__ . '/../../database/migrations' );
        $this->loadViewsFrom( __DIR__ . '/../../resources/views', 'arbory' );
    }

    /**
     * Load admin routes and register middleware
     */
    private function registerRoutesAndMiddlewares()
    {
        /**
         * @var Router $router
         */
        $router = $this->app[ 'router' ];

        $router->middlewareGroup( 'admin', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ArboryAdminHasAllowedIpMiddleware::class
        ] );

        $router->aliasMiddleware( 'arbory.admin_auth', ArboryAdminAuthMiddleware::class );
        $router->aliasMiddleware( 'arbory.admin_module_access', ArboryAdminModuleAccessMiddleware::class );
        $router->aliasMiddleware( 'arbory.admin_quest', ArboryAdminGuestMiddleware::class );
        $router->aliasMiddleware( 'arbory.admin_in_role', ArboryAdminInRoleMiddleware::class );
        $router->aliasMiddleware( 'arbory.admin_has_access', ArboryAdminHasAccessMiddleware::class );
        $router->aliasMiddleware( 'arbory.route_redirect', ArboryRouteRedirectMiddleware::class );
        $router->aliasMiddleware( 'arbory.admin_has_allowed_ip', ArboryAdminHasAllowedIpMiddleware::class );

        $this->app->booted( function( $app )
        {
            $app[ Kernel::class ]->prependMiddleware( ArboryRouteRedirectMiddleware::class );
        } );

        $this->registerAdminRoutes();
        $this->registerAppRoutes();
    }

    private function registerAdminRoutes()
    {
        $this->app['router']->group( [
            'as' => 'admin.',
            'middleware' => 'admin',
            'namespace' => '\Arbory\Base\Http\Controllers',
            'prefix' => config( 'arbory.uri' )
        ], function ()
        {
            include __DIR__ . '/../../routes/admin.php';
        } );
    }

    private function registerAppRoutes()
    {
        $adminRoutes = base_path( 'routes/admin.php' );

        if( !File::exists( $adminRoutes ) )
        {
            return;
        }

        $this->app['router']->group( [
            'as' => 'admin.',
            'middleware' => [ 'admin', 'arbory.admin_auth', 'arbory.admin_module_access' ],
            'namespace' => '',
            'prefix' => config( 'arbory.uri' )
        ],function () use ($adminRoutes)
        {
            include $adminRoutes;
        } );
    }

    /**
     * Register Arbory commands
     */
    private function registerCommands()
    {
        $commands = [
            'arbory.seed' => SeedCommand::class,
            'arbory.install' => InstallCommand::class,
            'arbory.generator' => GeneratorCommand::class,
            'arbory.generate' => GenerateCommand::class
        ];

        foreach( $commands as $containerKey => $commandClass )
        {
            $this->registerCommand( $containerKey, $commandClass );
        }
    }

    /**
     * @param string $containerKey
     * @param string $commandClass
     * @return void
     */
    private function registerCommand( string $containerKey, string $commandClass )
    {
        $this->app->singleton( $containerKey, function () use ( $commandClass )
        {
            return $this->app->make( $commandClass );
        } );

        $this->commands( $containerKey );
    }

    /**
     * Register Arbory module registry
     */
    private function registerModuleRegistry()
    {
        $this->app->singleton( 'arbory', function ()
        {
            return new Admin(
                $this->app['sentinel'],
                new Menu(),
                new AssetPipeline()
            );
        } );

        $this->app->singleton( Admin::class, function ()
        {
            return $this->app['arbory'];
        } );
    }

    /**
     * Register Arbory fields
     */
    private function registerFields()
    {
        $this->app->singleton( FieldTypeRegistry::class, function ( Application $app )
        {
            $fieldTypeRegistry = new FieldTypeRegistry();

            $fieldTypeRegistry->registerByType( 'integer', Hidden::class, 'int' );
            $fieldTypeRegistry->registerByType( 'string', Text::class, 'string' );
            $fieldTypeRegistry->registerByType( 'text', Textarea::class, 'string' );
            $fieldTypeRegistry->registerByType( 'longtext', Richtext::class, 'string' );
            $fieldTypeRegistry->registerByType( 'datetime', DateTime::class, 'string' );
            $fieldTypeRegistry->registerByType( 'boolean', Checkbox::class, 'bool' );

            $fieldTypeRegistry->registerByRelation( 'file', ArboryFile::class );
            $fieldTypeRegistry->registerByRelation( 'image', ArboryImage::class );
            $fieldTypeRegistry->registerByRelation( 'link', Link::class );

            return $fieldTypeRegistry;
        } );
    }

    /**
     * Register stubs used by generators
     */
    private function registerGeneratorStubs()
    {
        $this->app->singleton( StubRegistry::class, function( Application $app )
        {
            $stubRegistry = new StubRegistry();

            $stubRegistry->registerStubs(
                $app[ Filesystem::class ],
                base_path( 'vendor/arbory/arbory/stubs' )
            );

            return $stubRegistry;
        } );
    }

    /**
     * @return void
     */
    private function registerLocales()
    {
        config()->set( 'translator.locales', config( 'arbory.locales' ) );
        config()->set( 'translatable.locales', config( 'arbory.locales' ) );
    }

    /**
     * @return void
     */
    private function registerViewComposers()
    {
        $this->app->make('view')->composer('arbory::layout.main', LayoutViewComposer::class);
    }

    /**
     * @return void
     */
    private function registerValidationRules()
    {
        $isDestroyed = function( Request $request, $attribute )
        {
            $fieldSet = $request->request->get( 'fields' );
            $fields = $fieldSet->findFieldsByInputName( $attribute );
            $fields = array_reverse( $fields );

            foreach( $fields as $fieldName => $field )
            {
                if( $field instanceof HasMany )
                {
                    $attributeParts = explode( '.', $attribute );
                    $toManyIndex = array_search( $fieldName, $attributeParts, true );
                    $attributeParent = array_slice( $attributeParts, 0, $toManyIndex + 2 );
                    $attributeParent = implode( '.', $attributeParent );

                    $isDestroyed = array_get( $request->input( $attributeParent ), '_destroy' );

                    return filter_var( $isDestroyed, FILTER_VALIDATE_BOOLEAN );
                }
            }

            return false;
        };

        \Validator::extendImplicit( 'arbory_file_required', function( $attribute ) use ( $isDestroyed )
        {
            /** @var FieldSet $fields */
            $request = \request();
            $fields = $request->request->get( 'fields' );
            $field = $fields->findFieldByInputName( $attribute );
            $file = $request->file( $attribute );

            if( $isDestroyed( $request, $attribute ) )
            {
                return true;
            }

            if( !$field )
            {
                return (bool) $file;
            }

            return $field->getValue() || $file;
        } );

        \Validator::extendImplicit( 'arbory_require_one_localized', function( $attribute, $value ) use ( $isDestroyed )
        {
            /** @var FieldSet $fieldSet */
            $request = \request();
            $fieldSet = $request->request->get( 'fields' );
            $fields = $fieldSet->findFieldsByInputName( $attribute );
            $translatable = null;

            if( $isDestroyed( $request, $attribute ) )
            {
                return true;
            }

            foreach( array_reverse( $fields ) as $index => $field )
            {
                if ( $field instanceof Translatable )
                {
                    $translatable = $field;
                }
            }

            if ( !$translatable || $value )
            {
                return (bool) $value;
            }

            $attributeLocale = null;
            $checkLocales = $translatable->getLocales();

            foreach( $checkLocales as $index => $checkLocale )
            {
                if( str_contains( $attribute, $checkLocale ) )
                {
                    $attributeLocale = $checkLocale;
                    unset( $checkLocales[ $index ] );
                    break;
                }
            }

            foreach( $checkLocales as $index => $checkLocale )
            {
                $checkByAttribute = str_replace( $attributeLocale, $checkLocale, $attribute );
                $field = $fieldSet->findFieldByInputName( $checkByAttribute );

                if( $request->input( $checkByAttribute ) || ( $field->getValue() && $request->input( $checkByAttribute ) !== null ) )
                {
                    return true;
                }
            }

            return false;
        } );
    }

    /**
     * @return void
     */
    private function registerAssets()
    {
        \App::booted( function()
        {
            \Admin::assets()->js( '/js/admin.js' );
        } );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ 'arbory', 'arbory.seed', 'arbory.modules', 'arbory.menu' ];
    }
}
