<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

/**
 * Class Hidden
 * @package Arbory\Base\Admin\Form\Fields
 */
class Hidden extends AbstractField
{
    protected $style = 'raw';

    /**
     * @return Element
     */
    public function render()
    {
        return Html::input()
            ->addAttributes( [ 'data-name' => $this->getName() ] )
            ->setType( 'hidden' )
            ->setValue( $this->getValue() )
            ->setName( $this->getNameSpacedName() );
    }
}
