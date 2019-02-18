<?php


namespace Arbory\Base\Admin\Panels;


use Arbory\Base\Admin\Form\FieldSet;

class FieldSetPanel extends SimplePanel
{
    /**
     * @var FieldSet
     */
    protected $fieldSet;

    public function fields():FieldSet
    {
        return $this->fieldSet;
    }

    public function setFields(FieldSet $fieldSet)
    {
        $this->fieldSet = $fieldSet;

        return $this;
    }

    public function contents()
    {
        return $this->fieldSet->render();
    }
}