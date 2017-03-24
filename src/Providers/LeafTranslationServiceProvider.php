<?php namespace CubeSystems\Leaf\Providers;

use Waavi\Translation\TranslationServiceProvider;

/**
 * Class LeafTranslationServiceProvider
 * @package CubeSystems\Leaf\Providers
 */
class LeafTranslationServiceProvider extends TranslationServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCacheRepository();
        $this->registerFileLoader();
        $this->registerCacheFlusher();
    }
}
