<?php

namespace Arbory\Base\Admin\Form\Fields;

interface RepeatableNestedFieldInterface
{
    /**
     * @param $model
     * @param $index
     * @return mixed
     */
    public function getRelationFieldSet($model, $index);
}
