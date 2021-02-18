<?php

namespace Arbory\Base\Html\Elements\Inputs;

class Option extends AbstractInputField
{
    public function __construct($content = null)
    {
        parent::__construct('option', $content);
    }

    public function select()
    {
        $this->attributes()->put('selected', 'selected');

        return $this;
    }
}
