<?php

namespace Arbory\Base\Providers;

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
    /**
     * Bootstrap services.
     *
     * @param ViewFactory $view
     * @param Admin $admin
     * @return void
     */
    public function boot(ViewFactory $view, Admin $admin): void
    {
        $view->composer('arbory::layout.main', function (View $view) use ($admin) {
            $assets = $admin->assets();
            $assets->js('/js/admin.js');

            $view->with([
                'assets' => $assets,
                'assetsJs' => $assets->getJs(),
                'assetsCss' => $assets->getCss(),
                'inlineJs' => implode(PHP_EOL, $assets->getInlineJs()->all()),
                'inlineCss' => implode(PHP_EOL, $assets->getInlineCss()->all()),
                'user' => $admin->sentinel()->getUser(),
                'menu' => $this->buildMenu()->render(),
            ]);
        });

        $this->app->singleton(LayoutManager::class);
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
