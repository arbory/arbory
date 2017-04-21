<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Admin\Form\Fields\FieldInterface;
use CubeSystems\Leaf\Html\Elements\Content;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Support\Collection;

/**
 * Class AssociatedSetRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
class AssociatedSetRenderer
{
    /**
     * @var FieldInterface
     */
    protected $field;

    /**
     * @var array
     */
    protected $values;

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
        $this->values = (array) $field->getValue();
        $this->options = $options;
    }

    /**
     * @return \CubeSystems\Leaf\Html\Elements\Element
     */
    protected function getLabel()
    {
        return Html::label( $this->field->getLabel() );
    }

    /**
     * @return Content
     */
    protected function getAssociatedItemsList()
    {
        $content = new Content();

        $index = 0;

        foreach( $this->options as $value => $label )
        {
            $content[] = $this->getAssociatedItem(
                $this->field->getNameSpacedName() . '.' . $index,
                $value,
                $label
            );

            $index++;
        }

        return $content;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $label
     * @return Element
     */
    protected function getAssociatedItem( $name, $value, $label )
    {
        $checkbox = Html::checkbox( $label );
        $checkbox->setName( $name );
        $checkbox->setValue( $value );

        if( in_array( $value, $this->values, true ) )
        {
            $checkbox->select();
        }

        return Html::div( $checkbox )->addClass( 'type-associated-set-item' );
    }

    /**
     * @return Element
     */
    public function render()
    {
        $field = new FieldRenderer();
        $field->setType( 'associated-set' );
        $field->setName( $this->field->getName() );
        $field->setLabel( $this->getLabel() );
        $field->setValue( $this->getAssociatedItemsList() );

        return $field->render();
    }
}
