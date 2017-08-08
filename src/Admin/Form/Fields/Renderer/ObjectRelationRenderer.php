<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\ObjectRelation;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Database\Eloquent\Model;

class ObjectRelationRenderer
{
    /**
     * @var ObjectRelation
     */
    protected $field;

    /**
     * @param ObjectRelation $field
     */
    public function __construct( ObjectRelation $field )
    {
        $this->field = $field;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $item = $this->field->getModel();
        $block = Html::section( [
            $this->getRelatedElement(),
            $this->getRelationalElement()
        ] )->addAttributes( [ 'data-name' => $this->field->getName() ] );

        foreach( $this->field->getRelationFieldSet( $item )->getFields() as $field )
        {
            $block->append( $field->render() );
        }

        if( $this->field->isSingular() )
        {
            $field = new FieldRenderer();
            $field->setType( 'select' );
            $field->setName( $this->field->getName() );
            $field->setLabel( $this->field->getLabel() );

            $field->setValue( $block );

            return $field->render()->addClass( 'type-object-relation single' );
        }

        return $block->addClass( 'field type-object-relation' );
    }

    /**
     * @return Element
     */
    protected function getRelatedElement()
    {
        $value = $this->field->getValue();

        $title = Html::span( $value ? $value->related()->first() : null )->addClass( 'title' );

        return Html::div( Html::div( $title )->addClass( 'item' ) )->addClass( 'related' );
    }

    /**
     * @return Element
     */
    protected function getRelationalElement()
    {
        return Html::div( $this->getRelationalItemElements() )->addClass( 'relations' );
    }

    /**
     * @return array
     */
    protected function getRelationalItemElements()
    {
        $items = [];
        $relational = $this->field->getOptions();

        foreach($relational as $relation) {
            $items[] = $this->buildRelationalItemElement( $relation );
        }

        return $items;
    }

    /**
     * @param Model $model
     * @return Element
     */
    protected function buildRelationalItemElement( Model $model )
    {
        return Html::div(
            Html::span(
                (string) $model
            )->addClass( 'title' )
        )->addClass( 'item' )->addAttributes( [
            'data-key' => $model->getKey(),
            'data-level' => $model->getAttributeValue( $this->field->getIndentAttribute() )
        ] );
    }
}