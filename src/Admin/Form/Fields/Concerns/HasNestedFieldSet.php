<?php


namespace Arbory\Base\Admin\Form\Fields\Concerns;


use Arbory\Base\Admin\Form\FieldSet;

trait HasNestedFieldSet
{
    /**
     * @param FieldSet $fieldSet
     *
     * @return FieldSet
     */
    public function configureFieldSet(FieldSet $fieldSet)
    {
        return $fieldSet;
    }

    public function getNestedFieldSet( $model )
    {
        $fieldSet = new FieldSet($model, $this->getNamespacedName());

        return $this->configureFieldSet($fieldSet);
    }
}