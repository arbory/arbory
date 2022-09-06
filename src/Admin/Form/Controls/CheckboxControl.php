<?php

namespace Arbory\Base\Admin\Form\Controls;

use Arbory\Base\Exceptions\BadMethodCallException;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Elements\Inputs\Input as InputElement;

class CheckboxControl extends InputControl
{
    protected string $type = 'checkbox';

    /**
     * @var bool
     */
    protected bool $checked = false;

    /**
     * @throws BadMethodCallException
     */
    public function render(Element $control): Content
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

    public function isChecked(): bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }
}
