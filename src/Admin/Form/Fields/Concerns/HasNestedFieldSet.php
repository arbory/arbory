<?php

namespace Arbory\Base\Admin\Form\Fields\Concerns;

use Illuminate\Http\Request;
use Arbory\Base\Admin\Form\FieldSet;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\NestedFieldInterface;

trait HasNestedFieldSet
{
    /**
     * @return FieldSet
     */
    public function configureFieldSet(FieldSet $fieldSet)
    {
        return $fieldSet;
    }

    /**
     * @param  Model  $model
     * @return FieldSet|FieldInterface[]
     */
    public function getNestedFieldSet($model): FieldSet|array
    {
        $fieldSet = new FieldSet($model, $this->getNamespacedName());

        return $this->configureFieldSet($fieldSet);
    }

    /**
     * @param  FieldInterface|null  $parent
     */
    public function iterate(Request $request, callable $callback, $parent = null)
    {
        $fieldSet = $this->getNestedFieldSet($this->getModel());

        foreach ($fieldSet->all() as $field) {
            $callback($field, $parent, $request);

            if ($field instanceof NestedFieldInterface) {
                $field->iterate($field->getModel(), $request, $field);
            }
        }
    }
}
