<?php

namespace Arbory\Base\Admin\Form\Fields;

interface NestedFieldInterface
{
    /**
     * @param $model
     *
     * @return mixed
     */
    public function getNestedFieldSet($model);
}
