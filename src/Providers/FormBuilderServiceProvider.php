<?php

namespace Arbory\Base\Providers;

use Arbory\Base\Services\FieldTypeRegistry;
use Illuminate\Support\ServiceProvider;

class FormBuilderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(FieldTypeRegistry::class, function () {
            $registry = new FieldTypeRegistry();

            foreach ((array)config('arbory.field_types') as $type => $class) {
                $registry->register($type, $class);
            }

            return $registry;
        });
    }
}
