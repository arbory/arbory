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
     * @param Form $form
     *
     * @return mixed
     */
    public function setForm(Form $form);
}
