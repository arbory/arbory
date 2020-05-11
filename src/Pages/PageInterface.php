<?php

namespace Arbory\Base\Pages;

use Arbory\Base\Admin\Form\FieldSet;

interface PageInterface
{
    /**
     * @param FieldSet $fieldSet
     * @return void
     */
    public function prepareFieldSet(FieldSet $fieldSet);
}
