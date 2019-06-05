<?php

namespace Arbory\Base\Html\Elements\Inputs;

class Textarea extends AbstractInputField
{
    public function __construct($content = null)
    {
        parent::__construct('textarea', $content);
    }
}
