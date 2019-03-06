<?php


namespace Arbory\Base\Admin\Form\Fields;


interface NestedFieldInterface
{
    public function getNestedFieldSet( $model );

//    public function setItemRenderer(\Arbory\Base\Admin\Form\Fields\Renderer\Nested\ItemInterface $renderer);
}