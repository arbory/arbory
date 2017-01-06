<?php

namespace CubeSystems\Leaf\Html\Elements\Inputs;

use CubeSystems\Leaf\Exceptions\BadMethodCallException;

class Input extends AbstractInputField
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

    public function __construct( $content = null )
    {
        parent::__construct( 'input', $content );

        $this->setType( 'text' );
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

}
