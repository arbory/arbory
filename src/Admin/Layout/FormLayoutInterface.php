<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Admin\Form;

interface FormLayoutInterface extends LayoutInterface
{
    /**
     * @return mixed
     */
    public function getForm();

    /**
     * @return mixed
     */
    public function setForm(Form $form);
}
