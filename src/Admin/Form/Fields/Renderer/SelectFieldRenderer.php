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
     * @var FieldInterface
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
        return ( new Select )
            ->name( $this->field->getNameSpacedName() )
            ->options( $this->options )
            ->selected( $this->field->getValue() );
    }

    /**
     * @return Element
     */
    public function render()
    {
        $field = new FieldRenderer();
        $field->setType( 'select' );
        $field->setName( $this->field->getName() );
        $field->setLabel( $this->getLabel() );
        $field->setValue( $this->getSelectInput() );

        return $field->render();
    }
}
