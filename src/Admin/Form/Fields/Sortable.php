<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Http\Request;

class Sortable extends AbstractField implements ProxyFieldInterface
{
    /**
     * @var HasMany
     */
    protected $field;

    /**
     * @param string $name
     * @param HasMany $field
     */
    public function __construct( string $name, HasMany $field )
    {
        $this->field = $field;

        $this->field->setOrderBy( $name );

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
        )->addClass( 'nested type-sortable' )->addAttributes( [
            'data-sort-by' => $this->getName()
        ] );
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getField()->getLabel();
    }

    /**
     * @param string $label
     * @return HasMany
     */
    public function setLabel($label)
    {
        return $this->getField()->setLabel($label);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function beforeModelSave( Request $request )
    {
    }

    /**
     * @param Request $request
     * @return void
     */
    public function afterModelSave( Request $request )
    {
        $this->field->setFieldSet( $this->getFieldSet() );
        $this->field->afterModelSave( $request );
    }

    /**
     * @return HasMany
     */
    public function getSortableField()
    {
        return $this->field->setFieldSet( $this->getFieldSet() );
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        $this->field->setFieldSet( $this->getFieldSet() );

        return $this->getSortableField()->getRules();
    }

    /**
     * @return HasMany
     */
    public function getField(): FieldInterface
    {
        return $this->field;
    }
}
