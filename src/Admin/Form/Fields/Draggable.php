<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

class Draggable extends AbstractField
{
    /**
     * @var AbstractField
     */
    protected $field;

    /**
x     * @param string $name
     * @param AbstractField $field
     */
    public function __construct( string $name, AbstractField $field )
    {
        $this->field = $field;

        parent::__construct( $name );
    }

    /**
     * @return Element
     */
    public function render()
    {
        $this->field->setFieldSet( $this->getFieldSet() );

        return Html::div(
            $this->field->render()
        )->addClass( 'nested draggable' );
    }
}