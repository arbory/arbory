<?php

namespace Arbory\Base\Providers;

use Arbory\Base\Admin\Form\Fields\Styles\StyleManager;
use Arbory\Base\Services\FieldTypeRegistry;
use Illuminate\Support\ServiceProvider;

class FormBuilderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(FieldTypeRegistry::class, function ($app) {
            $registry = new FieldTypeRegistry($app);

            foreach ((array)config('arbory.field_types') as $type => $class) {
                $registry->register($type, $class);
            }

            return $registry;
        });

        $this->app->singleton(StyleManager::class, function ($app) {
            $defaultStyle = config('arbory.default_field_style');

            return new StyleManager($app, (array)config('arbory.field_styles'), $defaultStyle);
        });
    }
}
