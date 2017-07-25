<?php

namespace Arbory\Base\Providers;

use Arbory\Base\Services\AssetPipeline;
use Illuminate\Support\ServiceProvider;

class AssetServiceProvider extends ServiceProvider
{
    public function register()
    {
        \View::composer( '*layout*', function( \Illuminate\View\View $view )
        {
            /**
             * @var AssetPipeline $assetsPipeline
             */
            $assetsPipeline = \Admin::assets();

            $view->with( [
                'assetsJs' => $assetsPipeline->getJs(),
                'assetsCss' => $assetsPipeline->getCss(),
                'inlineJs' => implode( PHP_EOL, $assetsPipeline->getInlineJs()->all() ),
                'inlineCss' => implode( PHP_EOL, $assetsPipeline->getInlineCss()->all() )
            ] );
        } );
    }
}
