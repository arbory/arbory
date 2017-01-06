<?php

namespace CubeSystems\Leaf\Html\Elements\Inputs;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

abstract class AbstractInputField extends Element
{
    public function setValue( $value )
    {
        $this->attributes()->put( 'value', $value );

        return $this;
    }

    public function setName( $name )
    {
        $this->attributes()->put( 'name', $this->formatInputName( $name ) );
        $this->attributes()->put( 'id', $this->formatInputId() );

        return $this;
    }

    public function getLabel( $text )
    {
        return Html::label( $text )->addAttributes( [ 'for' => $this->attributes()->get( 'id' ) ] );
    }

    protected function formatInputName( $name )
    {
        $nameParts = preg_split( '/\./', $name, NULL, PREG_SPLIT_NO_EMPTY );

        $inputName = array_pull( $nameParts, 0 );

        if( count( $nameParts ) > 0 )
        {
            $inputName .= '[' . implode( '][', $nameParts ) . ']';
        }

        return $inputName;
    }

    protected function formatInputId()
    {
        return strtr( $this->attributes()->get( 'name' ), [ '[' => '_', ']' => '' ] );
    }
}
