<?php

namespace Arbory\Base\Admin\Panels;

use Arbory\Base\Admin\Form\FieldSet;

class FieldSetPanel extends Panel
{
    /**
     * @var FieldSet
     */
    protected $fieldSet;

    public function fields(): FieldSet
    {
        return $this->fieldSet;
    }

    public function setFields(FieldSet $fieldSet)
    {
        $this->fieldSet = $fieldSet;

        return $this;
    }

    public function getContent()
    {
        return $this->fieldSet->render();
    }
}
