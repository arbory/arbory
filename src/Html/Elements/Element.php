<?php

namespace CubeSystems\Leaf\Html\Elements;

use CubeSystems\Leaf\Html\Tag;

/**
 * Class Element
 * @package CubeSystems\Leaf\Html\Elements
 */
class Element
{
    /**
     * @var Attributes
     */
    protected $attributes;

    /**
     * @var Content
     */
    protected $content;

    public function __construct( $content = null )
    {
        if( $content !== null )
        {
            $this->append( $content );
        }
    }

    /**
     * @return Attributes
     */
    public function attributes()
    {
        if( $this->attributes === null )
        {
            $this->attributes = new Attributes;
        }

        return $this->attributes;
    }

    /**
     * @return Content
     */
    public function content()
    {
        if( $this->content === null )
        {
            $this->content = new Content;
        }

        return $this->content;
    }

    /**
     * @param string $name
     * @param string|null $content
     * @return Tag
     */
    public function tag( $name, $content = null )
    {
        return ( new Tag( $name ) )
            ->setAttributes( $this->attributes() )
            ->setContent( $content );
    }

    /**
     * @param string $class
     * @return $this
     */
    public function addClass( $class )
    {
        $this->attributes()->put( 'class', implode( ' ', [
            $this->attributes()->get('class'),
            $class
        ] ) );

        return $this;
    }

    /**
     * @param Element $item
     * @return $this
     */
    public function append( $item )
    {
        $this->content()->push( $item );

        return $this;
    }
}
