<?php

namespace CubeSystems\Leaf\Html\Elements\Inputs;

class CheckBox extends Input
{
    public function __construct( $content = null )
    {
        parent::__construct( $content );

        $this->setType( 'checkbox' );
    }

    public function __toString()
    {
        return parent::__toString() . ( $this->label !== null ? ' ' . (string) $this->label : null );
    }

    public function select()
    {
        $this->attributes()->put( 'checked', 'checked' );
    }
}
