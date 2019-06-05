<?php

namespace Arbory\Base\Admin\Form\Controls;

use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Elements\Inputs\Input as InputElement;

class CheckboxControl extends InputControl
{
    protected $type = 'checkbox';

    /**
     * @var bool
     */
    protected $checked = false;

    /**
     * @param Element $control
     *
     * @return Content
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    public function render(Element $control)
    {
        $control->setType($this->type);
        $input = parent::render($control);

        $content = new Content();

        if ($this->isReadOnly()) {
            $input->addAttributes(['disabled' => '']);

            if ($this->isChecked()) {
                $input->attributes()->forget(
                    ['id']
                );

                $hidden = (new InputElement())->setType('hidden');

                $hidden->setName($this->getName());
                $hidden->setValue($this->getValue());

                $content->push($hidden);
            }
        }

        if ($this->isChecked()) {
            $input->attributes()->put('checked', '');
        }

        $content->push($input);

        return $content;
    }

    /**
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->checked;
    }

    /**
     * @param bool $checked
     *
     * @return CheckboxControl
     */
    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }
}
