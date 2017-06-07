<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Http\Request;

class Sortable extends AbstractField
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
        )->addClass( 'nested sortable' )->addAttributes( [
            'data-sort-by' => $this->getName()
        ] );
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
        return $this->field;
    }
}