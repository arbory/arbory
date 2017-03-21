<?php


namespace CubeSystems\Leaf\Pages;


use CubeSystems\Leaf\Admin\Form\FieldSet;

interface PageInterface
{
    /**
     * @param FieldSet $fieldSet
     * @return void
     */
    public function prepareFieldSet( FieldSet $fieldSet );
}
