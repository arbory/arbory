<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

/**
 * Class Hidden
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class Hidden extends AbstractField
{
    /**
     * @return Element
     */
    public function render()
    {
        return Html::input()
            ->setType( 'hidden' )
            ->setValue( $this->getValue() )
            ->setName( $this->getNameSpacedName() );
    }
}
