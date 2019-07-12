<?php

namespace Arbory\Base\Admin\Panels;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Tools\Toolbox;

class FormPanel extends Panel
{
    /**
     * @var Form
     */
    protected $form;

    public function build()
    {
        $this->setTitle($this->form->getTitle());
        $this->setContent($this->form->fields()->render());
    }

    public function toolbox(Toolbox $toolbox): Toolbox
    {
        if ($this->form->getModel()->getKey()) {
            $toolbox = Toolbox::create(
                $this->form->getModule()->url(
                    'dialog',
                    ['dialog' => 'toolbox', 'id' => $this->form->getModel()->getKey()]
                )
            );
        }

        return $toolbox;
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @param  Form  $form
     *
     * @return FormPanel
     */
    public function setForm(Form $form): self
    {
        $this->form = $form;

        $this->build();

        return $this;
    }
}
