<?php

namespace Arbory\Base\Admin\Form\Fields;

interface ProxyFieldInterface
{
    /**
     * @return FieldInterface
     */
    public function getField(): FieldInterface;
}
