<?php

namespace CubeSystems\Leaf\Html\Elements\Inputs;

class Select extends AbstractInputField
{
    public function __construct( $content = null )
    {
        parent::__construct( 'select', $content );
    }
}
