<?php

namespace Arbory\Base\Html\Elements\Inputs;

class CheckBox extends Input
{
    public function __construct($content = null)
    {
        parent::__construct($content);

        $this->setType('checkbox');
    }

    public function select()
    {
        $this->attributes()->put('checked', 'checked');
    }
}
