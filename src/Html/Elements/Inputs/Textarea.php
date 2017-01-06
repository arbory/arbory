<?php

namespace CubeSystems\Leaf\Html\Elements\Inputs;

class Textarea extends AbstractInputField
{
    public function __construct( $content = null )
    {
        parent::__construct( 'textarea', $content );
    }
}
