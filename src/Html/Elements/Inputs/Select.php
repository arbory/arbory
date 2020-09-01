<?php

namespace Arbory\Base\Html\Elements\Inputs;

class Select extends AbstractInputField
{
    public function __construct($content = null)
    {
        parent::__construct('select', $content);
    }
}
