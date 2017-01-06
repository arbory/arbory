<?php

namespace CubeSystems\Leaf\Html;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Elements\Inputs\CheckBox;
use CubeSystems\Leaf\Html\Elements\Inputs\Input;
use CubeSystems\Leaf\Html\Elements\Inputs\Option;
use CubeSystems\Leaf\Html\Elements\Inputs\Select;
use CubeSystems\Leaf\Html\Elements\Inputs\Textarea;

class Html
{
    public static function div( $content = null )
    {
        return new Element( 'div', $content );
    }

    public static function span( $content = null )
    {
        return new Element( 'span', $content );
    }

    public static function ol( $content = null )
    {
        return new Element( 'ol', $content );
    }

    public static function ul( $content = null )
    {
        return new Element( 'ul', $content );
    }

    public static function li( $content = null )
    {
        return new Element( 'li', $content );
    }

    public static function button( $content = null )
    {
        return new Element( 'button', $content );
    }

    public static function menu( $content = null )
    {
        return new Element( 'menu', $content );
    }

    public static function i( $content = null )
    {
        return new Element( 'i', $content );
    }

    public static function label( $content = null )
    {
        return new Element( 'label', $content );
    }

    public static function input( $content = null )
    {
        return new Input( $content );
    }

    public static function checkbox( $content = null )
    {
        return new CheckBox( $content );
    }

    public static function select( $content = null )
    {
        return new Select( $content );
    }

    public static function option( $content = null )
    {
        return new Option( $content );
    }

    public static function textarea( $content = null )
    {
        return new Textarea( $content );
    }

    public static function image( $content = null )
    {
        return new Element( 'img', $content );
    }

    public static function link( $content = null )
    {
        return new Element( 'a', $content );
    }

    public static function abbr( $content = null )
    {
        return new Element( 'abbr', $content );
    }
}
