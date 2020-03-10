<?php

namespace Arbory\Base\Providers;

use Arbory\Base\Services\AssetPipeline;
use Illuminate\View\View;
use Arbory\Base\Menu\Menu;
use Arbory\Base\Admin\Admin;
use Arbory\Base\Menu\MenuFactory;
use Arbory\Base\Menu\MenuItemFactory;
use Illuminate\Support\ServiceProvider;
use Arbory\Base\Admin\Layout\LayoutManager;
use Illuminate\Contracts\View\Factory as ViewFactory;

class LayoutServiceProvider extends ServiceProvider
{
    protected const GOOGLE_MAPS_SRC = 'https://maps.googleapis.com/maps/api/js?libraries=places&key=';

    /**
     * @param ViewFactory $view
     * @param Admin $admin
     */
    public function boot(ViewFactory $view, Admin $admin): void
    {
        $assets = $admin->assets();

        $view->composer('arbory::layout.main', function (View $view) use ($assets, $admin) {
            $assets->css($assets->getMixUrl('css/application.css'));

            $assets->prependJs($assets->getMixUrl('js/application.js'));
            $assets->prependJs($assets->getMixUrl('js/includes.js'));
            $assets->prependJs($assets->getMixUrl('js/vendor.js'));
            $assets->prependJs($assets->getMixUrl('js/manifest.js'));

            $this->loadThirdPartyAssets($assets);

            $view->with([
                'assets' => $assets,
                'user' => $admin->sentinel()->getUser(),
                'menu' => $this->buildMenu()->render(),
            ]);
        });

        $view->composer('arbory::layout.public', function (View $view) use ($assets) {
            $assets->css($assets->getMixUrl('css/application.css'));
            $assets->css($assets->getMixUrl('css/controllers/sessions.css'));

            $view->with([
                'assets' => $assets,
            ]);
        });

        $this->app->singleton(LayoutManager::class);
    }

    /**
     * @param AssetPipeline $assets
     * @return void
     */
    protected function loadThirdPartyAssets(AssetPipeline $assets): void
    {
        $googleMapsAPIKey = config('arbory.services.google.maps_api_key');

        if ($googleMapsAPIKey) {
            $assets->prependJs(self::GOOGLE_MAPS_SRC . $googleMapsAPIKey);
        }
    }

    /**
     * @return Menu
     */
    protected function buildMenu(): Menu
    {
        $itemFactory = $this->app->make(MenuItemFactory::class);
        $factory = new MenuFactory($itemFactory);

        return $factory->build(config('arbory.menu'));
    }
}
