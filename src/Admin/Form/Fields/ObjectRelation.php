<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\ObjectRelationRenderer;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Content\Relation;
use Arbory\Base\Nodes\Node;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ObjectRelation extends AbstractField
{
    /**
     * @var string
     */
    protected $relatedModelType;

    /**
     * @var Collection
     */
    protected $options;

    /**
     * @var string|null
     */
    protected $indentAttribute;

    /**
     * @var int|null
     */
    protected $limit = 1;

    /**
     * @param string $name
     * @param string|Collection $relatedModelTypeOrCollection
     * @param int $limit
     */
    public function __construct( $name, $relatedModelTypeOrCollection, $limit = 0 )
    {
        $this->relatedModelType = $relatedModelTypeOrCollection;
        $this->limit = $limit;

        if( $relatedModelTypeOrCollection instanceof Collection )
        {
            $this->options = $relatedModelTypeOrCollection;

            if( !$relatedModelTypeOrCollection->isEmpty() )
            {
                $this->relatedModelType = ( new \ReflectionClass( $relatedModelTypeOrCollection->first() ) )->getName();
            }
        }

        if( $this->relatedModelType === Node::class )
        {
            $this->indentAttribute = 'depth';
        }

        parent::__construct( $name );
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     * @throws \ReflectionException
     */
    public function render()
    {
        return ( new ObjectRelationRenderer( $this ) )->render();
    }

    /**
     * @return bool
     */
    public function hasIndentation()
    {
        return (bool) $this->getIndentAttribute();
    }

    /**
     * @return string
     */
    public function getIndentAttribute()
    {
        return $this->indentAttribute;
    }

    /**
     * @param string $indentAttribute
     */
    public function setIndentAttribute( string $indentAttribute )
    {
        $this->indentAttribute = $indentAttribute;
    }

    /**
     * @return Relation|Collection|null
     */
    public function getValue()
    {
        if( !$this->value )
        {
            $relation = $this->getModel()->morphMany( Relation::class, 'owner' )->where( 'name', $this->getName() );

            $this->value = $this->isSingular() ? $relation->first() : $relation->get();
        }

        return $this->value;
    }

    /**
     * @param Request $request
     * @return void
     */
    public function beforeModelSave( Request $request )
    {
        $namespacedName = $this->getNameSpacedName();

        $request->merge( [
            'relations' => [ $this->getName() => $request->input( $namespacedName ) ]
        ] );

        $request->except( $namespacedName );
    }

    /**
     * @param Request $request
     * @return void
     * @throws \Exception
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function afterModelSave( Request $request )
    {
        $attributes = $request->input( 'relations.' . $this->getName() );
        $relatedIds = array_get( $attributes, 'related_id' );
        $relatedType = array_get( $attributes, 'related_type' );

        $ownerName = $this->getName();
        $ownerId = $this->getModel()->getKey();
        $ownerType = ( new \ReflectionClass( $this->getModel() ) )->getName();

        if( !$relatedType )
        {
            return;
        }

        if( $this->isSingular() )
        {
            $relation = Relation::firstOrNew( [
                'name' => $ownerName,
                'owner_id' => $ownerId,
                'owner_type' => $ownerType
            ] );

            $relation->fill( [
                'related_id' => $relatedIds,
                'related_type' => $relatedType
            ] );

            $relation->save();

            return;
        }

        $relatedIds = explode( ',', $relatedIds );

        foreach( $relatedIds as $id )
        {
            if( !$id )
            {
                continue;
            }

            $relation = Relation::firstOrNew( [
                'name' => $ownerName,
                'owner_id' => $ownerId,
                'owner_type' => $ownerType,
                'related_id' => $id,
                'related_type' => $relatedType
            ] );

            $relation->save();
        }

        $this->deleteOldRelations( $relatedIds );
    }

    /**
     * @param array $updatedRelationIds
     * @return void
     * @throws \Exception
     */
    protected function deleteOldRelations( $updatedRelationIds )
    {
        if( $this->isSingular() )
        {
            return;
        }

        /**
         * @var Relation $relation
         */
        foreach( $this->getValue() as $relation )
        {
            if( !in_array( $relation->related_id, $updatedRelationIds ) )
            {
                $relation->delete();
            }
        }
    }

    /**
     * @param Model $model
     * @return bool
     */
    public function hasRelationWith( Model $model ): bool
    {
        $key = $model->getKey();
        $value = $this->getValue();

        if( !$value )
        {
            return false;
        }

        if( $this->isSingular() )
        {
            return $value->related_id === $key;
        }

        return $value->contains( 'related_id', $key );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Collection
     */
    public function getOptions()
    {
        return $this->options ?: $this->relatedModelType::all()->mapWithKeys( function( $item )
        {
            return [ $item->getKey() => $item ];
        } );
    }

    /**
     * @param Collection $options
     */
    public function setOptions( Collection $options )
    {
        $this->options = $options;
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return bool
     */
    public function isSingular()
    {
        return $this->getLimit() === 1;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->getInnerFieldSet()->getRules();
    }

    /**
     * @return FieldSet
     */
    public function getInnerFieldSet()
    {
        $fieldSet = new FieldSet( $this->getModel(), $this->getNameSpacedName() );

        $value = $this->getValue();
        $ids = null;

        if( $value )
        {
            if( $this->isSingular() )
            {
                $ids = $value->related_id;
            }
            else
            {
                $ids = $value->map( function( $item )
                {
                    return $item->related_id;
                } )->implode( ',' );
            }
        }

        $fieldSet->add( new Hidden( 'related_id' ) )->setValue( $ids )->rules( implode( '|', $this->rules ) );
        $fieldSet->add( new Hidden( 'related_type' ) )->setValue( ( new \ReflectionClass( $this->relatedModelType ) )->getName() );

        return $fieldSet;
    }

    /**
     * @return string
     */
    public function getRelatedModelType(): string
    {
        return $this->relatedModelType;
    }
}