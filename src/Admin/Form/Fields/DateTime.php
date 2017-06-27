<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Html\Elements\Element;

/**
 * Class DateTime
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class DateTime extends Text
{
    /**
     * @param string $name
     */
    public function __construct( $name )
    {
        parent::__construct( $name );
    }

    /**
     * @return Element
     */
    public function render()
    {
        return ( new Renderer\DateFieldRenderer( $this ) )->render();
    }
}
