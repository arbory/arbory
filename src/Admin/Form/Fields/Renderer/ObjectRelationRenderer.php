<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\ObjectRelation;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Database\Eloquent\Collection;
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
        $contents = Html::div( $this->getAvailableRelationElement() )->addClass( 'contents' );
        $block = Html::section()->addAttributes( [ 'data-name' => $this->field->getName() ] );
        $relatedItemsElement = $this->getRelatedItemsElement();

        if( $this->field->isSingular() )
        {
            $contents->append( $relatedItemsElement );
        }
        else
        {
            $contents->prepend( $relatedItemsElement );
        }

        foreach( $this->field->getRelationFieldSet( $item )->getFields() as $field )
        {
            $block->append( $field->render() );
        }

        if( $this->field->isSingular() )
        {
            $block->append( $contents );

            $field = new FieldRenderer();
            $field->setType( 'select' );
            $field->setName( $this->field->getName() );
            $field->setLabel( $this->field->getLabel() );

            $field->setValue( $block );

            return $field->render()->addClass( 'type-object-relation single' );
        }

        $block->prepend( Html::div( Html::label( $this->field->getName() ) )->addClass( 'label-wrap' ) );
        $block->append( $contents );

        return $block->addClass( 'field type-object-relation multiple' );
    }

    /**
     * @return Element
     */
    protected function getRelatedItemsElement()
    {
        $items = [];
        $values = $this->field->getValue();

        if( $values )
        {
            $values = $values instanceof Collection ? $values : new Collection( [ $values ] );

            foreach( $values as $value )
            {
                $relation = $value->related()->first();

                if( $relation )
                {
                    $items[] = $this->buildRelationalItemElement( $relation );
                }
            }
        }

        return Html::div( $items )->addClass( 'related' );
    }

    /**
     * @return Element
     */
    protected function getAvailableRelationElement()
    {
        return Html::div( $this->getAvailableRelationalItemsElement() )->addClass( 'relations' );
    }

    /**
     * @return array
     */
    protected function getAvailableRelationalItemsElement()
    {
        $items = [];
        $relational = $this->field->getOptions();

        foreach( $relational as $relation )
        {
            if( $this->field->hasRelationWith( $relation ) )
            {
                continue;
            }

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