<?php

namespace Arbory\Base\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PropertyRemover
{
    /**
     * @param Model $model
     * @param string $prefix
     * @return Model
     */
    public function remove(Model $model, string $prefix): Model
    {
        foreach (array_keys($model->getAttributes()) as $attribute) {
            if (Str::startsWith($attribute, $prefix)) {
                unset($model->{$attribute});
            }
        }

        return $model;
    }
}
