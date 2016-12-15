<?php

namespace CubeSystems\Leaf\Html\Elements\Inputs;

use CubeSystems\Leaf\Html\Elements\Element;

class Option extends Element
{
    protected $content;

    protected $selected;

    public function __construct( $value, $content )
    {
        $this->setValue( $value );
        $this->setContent( $content );
    }

    public function __toString()
    {
        return (string) $this->tag( 'option', $this->content );
    }

    public function setValue( $value )
    {
        $this->value = $value;

        $this->attributes()->put( 'value', $value );

        return $this;
    }

    public function setContent( $content )
    {
        $this->content = $content;

        return $this;
    }

    public function select()
    {
        $this->attributes()->put( 'selected', 'selected' );
    }
}
