<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Nested;

use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\RenderOptionsInterface;

interface ItemInterface extends RenderOptionsInterface
{
    /**
     * @param FieldInterface $field
     * @param FieldSet       $fieldSet
     * @param null           $index
     * @param mixed          $parameters
     *
     * @return mixed
     */
    public function __invoke(FieldInterface $field, FieldSet $fieldSet, $index = null, array $parameters = []);
}
