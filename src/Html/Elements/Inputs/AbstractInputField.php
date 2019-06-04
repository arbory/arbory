<?php

namespace Arbory\Base\Html\Elements\Inputs;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;

abstract class AbstractInputField extends Element
{
    /**
     * @param mixed $value
     * @return self
     */
    public function setValue($value)
    {
        $this->attributes()->put('value', $value);

        return $this;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->attributes()->put('name', Element::formatName($name));
        $this->attributes()->put('id', $this->formatInputId());

        return $this;
    }

    /**
     * @param string $text
     * @return Element
     */
    public function getLabel($text)
    {
        return Html::label($text)->addAttributes(['for' => $this->attributes()->get('id')]);
    }

    /**
     * @return string
     */
    protected function formatInputId()
    {
        return rtrim(strtr($this->attributes()->get('name'), ['[' => '_', ']' => '']), '_');
    }
}
