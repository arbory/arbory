<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Widgets\Select;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;

class SelectFieldRenderer
{
    /**
     * @var \Arbory\Base\Admin\Form\Fields\Select
     */
    protected $field;

    /**
     * @var array
     */
    protected $value;

    /**
     * @var Collection
     */
    protected $options;

    /**
     * AssociatedSetRenderer constructor.
     * @param FieldInterface $field
     * @param Collection $options
     */
    public function __construct( FieldInterface $field, Collection $options )
    {
        $this->field = $field;
        $this->value = $field->getValue();
        $this->options = $options;
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    protected function getLabel()
    {
        return Html::label( $this->field->getLabel() );
    }

    /**
     * @return Select
     */
    protected function getSelectInput()
    {
        $select = ( new Select )
            ->name( $this->field->getNameSpacedName() )
            ->options( $this->options )
            ->selected( $this->field->getValue() );

        if( $this->field->isMultiple() )
        {
            $select->name( $this->field->getNameSpacedName() . '[]' );
        }

        return $select;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $selectInput = $this->getSelectInput();
        $field = new FieldRenderer();
        $field->setType( 'select' );
        $field->setName( $this->field->getName() );
        $field->setLabel( $this->getLabel() );

        if( $this->field->isMultiple() )
        {
            $selectInput->attributes( [
                'multiple'
            ] );
        }

        $field->setValue( $selectInput );

        return $field->render();
    }
}
