<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\ObjectRelationGroupedRenderer;
use Arbory\Base\Admin\Form\Fields\Renderer\ObjectRelationRenderer;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Content\Relation;
use Arbory\Base\Nodes\Node;
use Closure;
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
    protected $limit;

    /**
     * @var string
     */
    private $groupByAttribute;

    /**
     * @var Closure|null
     */
    private $groupByGetName;

    /**
     * @var bool
     */
    private $default = true;

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
        $renderer = $this->isGrouped() ?
            ObjectRelationGroupedRenderer::class :
            ObjectRelationRenderer::class;

        return ( new $renderer( $this ) )->render();
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
     * @return self
     */
    public function setIndentAttribute( string $indentAttribute = null )
    {
        $this->indentAttribute = $indentAttribute;

        return $this;
    }

    /**
     * @param Model|bool $default
     * @return self
     */
    public function default( $default )
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @param string $attribute
     * @param Closure $groupName
     * @return self
     */
    public function groupBy( string $attribute, Closure $groupName = null )
    {
        $this->setIndentAttribute( null );

        $this->groupByAttribute = $attribute;
        $this->groupByGetName = $groupName;

        return $this;
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
        $request->except( $this->getNameSpacedName() );
    }

    /**
     * @param Request $request
     * @return void
     * @throws \Exception
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function afterModelSave( Request $request )
    {
        $attributes = $request->input( $this->getNameSpacedName() );
        $relationIds = explode( ',', array_get( $attributes, 'related_id' ) );
        $value = $this->getValue();

        if( $this->isSingular() )
        {
            $id = reset( $relationIds );

            if( !$id && $value instanceof Model )
            {
                $this->deleteOldRelations();

                return;
            }

            $this->saveOne( $id );
        }
        else
        {
            $this->saveMany( $relationIds );
            $this->deleteOldRelations( $relationIds );
        }
    }

    /**
     * @param int $relationId
     * @return void
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected function saveOne( $relationId )
    {
        if( !$relationId )
        {
            return;
        }

        $relation = Relation::firstOrNew( [
            'name' => $this->getName(),
            'owner_id' => $this->getOwnerId(),
            'owner_type' => $this->getOwnerType()
        ] );

        $relation->fill( [
            'related_id' => $relationId,
            'related_type' => $this->getRelatedModelType()
        ] );

        $relation->save();
    }

    /**
     * @param int[] $relationIds
     * @return void
     */
    protected function saveMany( $relationIds )
    {
        foreach( $relationIds as $id )
        {
            if( !$id )
            {
                continue;
            }

            $relation = Relation::firstOrNew( [
                'name' => $this->getName(),
                'owner_id' => $this->getOwnerId(),
                'owner_type' => $this->getOwnerType(),
                'related_id' => $id,
                'related_type' => $this->getRelatedModelType()
            ] );

            $relation->save();
        }
    }

    /**
     * @param array $updatedRelationIds
     * @return void
     * @throws \Exception
     */
    protected function deleteOldRelations( $updatedRelationIds = [] )
    {
        $relations = $this->getValue();

        if ($this->isSingular())
        {
            $relations = [ $relations ];
        }

        foreach( $relations as $relation )
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
        $values = collect();

        if ( $this->hasDefault() )
        {
            $values->push( null );
        }

        $values = $values->merge($this->options ?: $this->relatedModelType::all()->mapWithKeys( function( $item )
        {
            return [ $item->getKey() => $item ];
        } ) );

        return $this->groupByAttribute ? $values->groupBy( $this->groupByAttribute ) : $values;
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

    /**
     * @return string|null
     */
    protected function getOwnerType()
    {
        try
        {
            return ( new \ReflectionClass( $this->getModel() ) )->getName();
        }
        catch( \ReflectionException $e )
        {
            return null;
        }
    }

    /**
     * @return mixed
     */
    protected function getOwnerId()
    {
        return $this->getModel()->getKey();
    }

    /**
     * @return string
     */
    public function getGroupByAttribute()
    {
        return $this->groupByAttribute;
    }

    /**
     * @return Closure|null
     */
    public function getGroupByGetName()
    {
        return $this->groupByGetName;
    }

    /**
     * @return bool
     */
    public function isGrouped()
    {
        return (bool) $this->groupByAttribute;
    }

    /**
     * @return bool
     */
    private function hasDefault()
    {
        return $this->isSingular() && $this->default;
    }
}