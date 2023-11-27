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
use Laragear\TwoFactor\Contracts\TwoFactorAuthenticatable;

class LayoutServiceProvider extends ServiceProvider
{
    protected const GOOGLE_MAPS_SRC = 'https://maps.googleapis.com/maps/api/js?libraries=places&key=';

    /**
     * @param  ViewFactory  $view
     * @param  Admin  $admin
     */
    public function boot(ViewFactory $view, Admin $admin): void
    {
        $assets = $admin->assets();

        $view->composer('arbory::layout.main', function (View $view) use ($assets, $admin) {
            $assets->css(mix('css/application.css', 'vendor/arbory'));
            $assets->css(mix('css/material-icons.css', 'vendor/arbory'));

            $assets->prependJs(mix('js/application.js', 'vendor/arbory'));
            $assets->prependJs(mix('js/includes.js', 'vendor/arbory'));
            $assets->prependJs(mix('js/vendor.js', 'vendor/arbory'));
            $assets->prependJs(mix('js/manifest.js', 'vendor/arbory'));

            $this->loadThirdPartyAssets($assets);

            $user = $admin->sentinel()->getUser();

            $view->with([
                'assets' => $assets,
                'two_factor_auth_alert' => $this->viewTwoFactorAuthAlert($user),
                'user' => $user,
                'menu' => $this->buildMenu()->render(),
            ]);
        });

        $view->composer('arbory::layout.public', function (View $view) use ($assets) {
            $assets->css(mix('css/application.css', 'vendor/arbory'));
            $assets->css(mix('css/controllers/sessions.css', 'vendor/arbory'));

            $view->with([
                'assets' => $assets,
            ]);
        });

        $this->app->singleton(LayoutManager::class);
    }

    protected function viewTwoFactorAuthAlert(TwoFactorAuthenticatable $user): bool
    {
        return config('two-factor.mandatory') && !$user->hasTwoFactorEnabled();
    }

    /**
     * @param  AssetPipeline  $assets
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
