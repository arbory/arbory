<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Admin\Form\Fields\FieldInterface;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

/**
 * Class BaseRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
abstract class BaseRenderer
{
    /**
     * @var FieldInterface
     */
    protected $field;

    /**
     * @var string
     */
    protected $type = 'text';

    /**
     * InputFieldRenderer constructor.
     * @param FieldInterface $field
     */
    public function __construct( FieldInterface $field )
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getFieldType()
    {
        return $this->type;
    }

    /**
     * @return Element
     */
    protected function getLabel()
    {
        return Html::label( $this->field->getLabel() )->addAttributes( [ 'for' => $this->field->getNameSpacedName() ] );
    }

    /**
     * @return Element
     */
    abstract protected function getInput();

    /**
     * @return Element
     */
    public function render()
    {
        return Html::div( [
            Html::div( $this->getLabel() )->addClass( 'label-wrap' ),
            Html::div( $this->getInput() )->addClass( 'value' )
        ] )
            ->addClass( 'field type-' . $this->getFieldType() )
            ->addAttributes( [
                'data-name' => $this->field->getName()
            ] );
    }

}
