<?php

namespace CubeSystems\Leaf\Html\Elements\Inputs;

use CubeSystems\Leaf\Exceptions\BadMethodCallException;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Elements\Label;

class Input extends Element
{
    protected static $types = [
        'text',
        'radio',
        'checkbox',
        'email',
        'hidden',
        'password',
        'image',
        'number',
        'file',
        'url',
        'tel',
        'search',
        'color',
        'date',
        'month',
        'week',
        'range',
        'time'
    ];

    protected $label;

    public function __construct( $content = null )
    {
        parent::__construct( $content );

        $this->setType( 'text' );
    }

    public function __toString()
    {
        return (string) $this->tag( 'input' );
    }

    public function setName( $name )
    {
        $this->attributes()->put( 'name', $this->formatInputName( $name ) );
        $this->attributes()->put( 'id', $this->formatInputId() );

        return $this;
    }

    public function setType( $type )
    {
        if( !in_array( $type, static::$types, true ) )
        {
            throw new BadMethodCallException( 'Input type "' . $type . '" is not allowed' );
        }

        $this->attributes()->put( 'type', $type );

        return $this;
    }

    public function setValue( $value )
    {
        $this->attributes()->put( 'value', $value );

        return $this;
    }

    public function label( $text )
    {
        if( $this->label === null )
        {
            $this->label = new Label( $this->attributes()->get( 'id' ), $text );
        }

        return $this->label;
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
