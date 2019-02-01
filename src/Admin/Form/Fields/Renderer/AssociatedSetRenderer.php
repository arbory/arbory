<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Elements\Inputs\Input;
use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;

/**
 * Class AssociatedSetRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class AssociatedSetRenderer extends ControlFieldRenderer
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
     * AssociatedSetRenderer constructor.
     * @param FieldInterface $field
     */
    public function __construct( FieldInterface $field )
    {
        $this->field = $field;
        $this->values = (array) $field->getValue();
    }

    /**
     * @return Content
     */
    protected function getAssociatedItemsList()
    {
        $content = new Content();

        $index = 0;

        foreach( $this->field->getOptions() as $value => $label )
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
        $checkbox = Html::checkbox( ( new Input )->setName( $name )->getLabel( $label ) );
        $checkbox->setName( $name );
        $checkbox->setValue( $value );

        if( in_array( $value, $this->values, true ) )
        {
            $checkbox->select();
        }

        return Html::div( $checkbox )
                   ->addClass( 'type-associated-set-item' );
    }

    /**
     * @return Element
     */
    public function render()
    {
//        $field = new FieldRenderer();
//        $field->setType( 'associated-set' );
//        $field->setName( $this->field->getName() );
//        $field->setLabel( $this->getLabel() );
//        $field->setValue(  );
//
//        return $field->render();

        return $this->getAssociatedItemsList();
    }
}
