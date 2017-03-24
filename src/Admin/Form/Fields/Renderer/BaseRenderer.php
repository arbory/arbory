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
        return Html::label( $this->field->getLabel() );
    }

    /**
     * @return Element
     */
    abstract protected function getInput();

    /**
     * @param Element|null $label
     * @param Element|null $value
     * @return Element
     */
    protected function buildField( Element $label = null, Element $value = null )
    {
        $template = Html::div()
            ->addClass( 'field type-' . $this->getFieldType() )
            ->addAttributes( [
                'data-name' => $this->field->getName()
            ] );

        if( $label )
        {
            $template->append( Html::div( $label )->addClass( 'label-wrap' ) );
        }

        if( $value )
        {
            $template->append( Html::div( $value )->addClass( 'value' ) );
        }

        return $template;
    }

    /**
     * @return Element
     */
    public function render()
    {
        return $this->buildField( $this->getLabel(), $this->getInput() );
    }
}
