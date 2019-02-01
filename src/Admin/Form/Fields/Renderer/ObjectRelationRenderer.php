<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\ObjectRelation;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ObjectRelationRenderer implements Renderable
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
        $name = $this->field->getName();
        $limit = $this->field->getLimit();
        $label = $this->field->getLabel();
        $relates = strtolower( class_basename( $this->field->getRelatedModelType() ) );
        $class = 'field type-object-relation relates-' . $relates;
        $attributes = [
            'data-name' => $name,
            'data-limit' => $limit
        ];

        $contents = Html::div( $this->getAvailableRelationElement() )->addClass( 'contents' );
        $relatedItemsElement = $this->getRelatedItemsElement();
        $block = Html::div();

        if( $this->field->hasIndentation() )
        {
            $attributes += [ 'data-indent' => $this->field->getIndentAttribute() ];
        }

        if( $this->field->isSingular() )
        {
            $contents->append( $relatedItemsElement );
        }
        else
        {
            $contents->prepend( $relatedItemsElement );
        }

        foreach( $this->field->getInnerFieldSet()->getFields() as $field )
        {
            $block->append( $field->render() );
        }

        if( $this->field->isSingular() )
        {
            $block->append( $contents );

            $field = new FieldRenderer();
            $field->setType( 'select' );
            $field->setName( $name );
            $field->setLabel( $label );

            $field->setValue( $block );

            return $field->render()->addClass( $class . ' single' )->addAttributes( $attributes );
        }

        if( $limit > 1 )
        {
            $label .= Html::strong( sprintf( ' (%s)', $limit ) );
        }

        $block->prepend( Html::div( Html::label( $label ) )->addClass( 'label-wrap' ) );
        $block->append( $contents );

        return $block->addClass( $class . ' multiple' )->addAttributes( $attributes );
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
            if ( $relation instanceof Model )
            {
                $items[] = $this->buildRelationalItemElement( $relation, $this->field->hasRelationWith( $relation ) );
            }
            else
            {
                $items[] = $this->buildRelationalItemElement( $relation );
            }
        }

        return $items;
    }

    /**
     * @param mixed $value
     * @param bool $isRelated
     * @return Element
     */
    protected function buildRelationalItemElement( $value, bool $isRelated = false )
    {
        $element = Html::div(
            Html::span(
                (string) $value
            )->addClass( 'title' )
        )->addClass( 'item' );

        if( $value instanceof Model )
        {
            $element->addAttributes( [
                'data-key' => $value->getKey(),
                'data-level' => $value->getAttributeValue( $this->field->getIndentAttribute() ),
                'data-inactive' => $isRelated && $this->field->hasIndentation() ? 'true' : 'false'
            ] );
        }

        return $element;
    }
}