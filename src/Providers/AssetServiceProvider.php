<?php

namespace CubeSystems\Leaf\Providers;

use CubeSystems\Leaf\Services\AssetPipeline;
use Illuminate\Support\ServiceProvider;
use View;

class AssetServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton( AssetPipeline::class );

        View::composer( '*layout*', function( \Illuminate\View\View $view )
        {
            /**
             * @var AssetPipeline $assetsPipeline
             */
            $assetsPipeline = $this->app->make( AssetPipeline::class );

            $view->with( [
                'assetsJs' => $assetsPipeline->getJs(),
                'assetsCss' => $assetsPipeline->getCss(),
                'inlineJs' => implode( PHP_EOL, $assetsPipeline->getInlineJs()->all() ),
                'inlineCss' => implode( PHP_EOL, $assetsPipeline->getInlineCss()->all() )
            ] );
        } );
    }
}