<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Widgets\Select;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;

class SelectFieldRenderer extends ControlFieldRenderer
{
    /**
     * @var \Arbory\Base\Admin\Form\Fields\Select
     */
    protected $field;

    /**
     * @return \Arbory\Base\Html\Elements\Inputs\Select
     */
    protected function getElement()
    {
        $select = ( new Select )
            ->options( $this->field->getOptions() )
            ->selected( $this->field->getValue() );

        if( $this->field->isMultiple() )
        {
            $select->attributes(['multiple' => 'multiple']);

            $select->name( $this->field->getNameSpacedName() . '[]' );
        }

        return $select->render();
    }
}
