<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Widgets\Select;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;

class SelectFieldRenderer extends InputFieldRenderer
{
    /**
     * @var \Arbory\Base\Admin\Form\Fields\Select
     */
    protected $field;

    /**
     * @return \Arbory\Base\Html\Elements\Inputs\Select
     */
    public function render()
    {
        $select = ( new Select )
            ->name( $this->field->getNameSpacedName() )
            ->options( $this->field->getOptions() )
            ->selected( $this->field->getValue() )
            ->attributes($this->getAttributes());

        if( $this->field->isMultiple() )
        {
            $select->name( $this->field->getNameSpacedName() . '[]' );
        }

        return $select->render();
    }
}
