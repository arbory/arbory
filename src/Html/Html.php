<?php

namespace CubeSystems\Leaf\Html;

use CubeSystems\Leaf\Html\Elements\Div;
use CubeSystems\Leaf\Html\Elements\Li;
use CubeSystems\Leaf\Html\Elements\Ul;

class Html
{
    /**
     * @param mixed|null $content
     * @return Div
     */
    public static function div( $content = null )
    {
        return new Div( $content );
    }

    /**
     *
     */
    public static function ul()
    {
        return new Ul( $content = null );
    }

    /**
     *
     */
    public static function li()
    {
        return new Li( $content = null );
    }
}
