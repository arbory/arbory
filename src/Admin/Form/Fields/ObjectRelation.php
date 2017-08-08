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
     * @var \Closure;
     */
    protected $fieldSetCallback;

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
     */
    public function __construct( $name, $relatedModelTypeOrCollection )
    {
        $this->relatedModelType = $relatedModelTypeOrCollection;

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
        // TODO: implementation for multi relation field
        if( !$this->isSingular() )
        {
            return null;
        }

        $this->fieldSetCallback = function( FieldSet $fields ) {
            $value = $this->getValue();

            $fields->add( new Hidden( 'related_id' ) )->setValue( $value ? $value->related()->first()->getKey() : null );
            $fields->add( new Hidden( 'related_type' ) )->setValue( ( new \ReflectionClass( $this->relatedModelType ) )->getName() );
        };

        return ( new ObjectRelationRenderer( $this ) )->render();
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
     * @param Model $relatedModel
     * @return FieldSet
     */
    public function getRelationFieldSet( Model $relatedModel )
    {
        $fieldSet = new FieldSet( $relatedModel, $this->getNameSpacedName() );
        $fieldSetCallback = $this->fieldSetCallback;

        $fieldSetCallback( $fieldSet );

        $fieldSet->add( new Hidden( $relatedModel->getKeyName() ) )
            ->setValue( $relatedModel->getKey() );

        return $fieldSet;
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
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function afterModelSave( Request $request )
    {
        $attributes = $request->input( 'relations.' . $this->getName() );
        $relatedId = array_get( $attributes, 'related_id' );
        $relatedType = array_get( $attributes, 'related_type' );
        $relation = Relation::firstOrNew( [
            'name' => $this->getName(),
            'owner_id' => $this->getModel()->getKey(),
            'owner_type' => ( new \ReflectionClass( $this->getModel() ) )->getName()
        ] );

        if( $relatedId && $relatedType )
        {
            $relation->fill( [
                'related_id' => $relatedId,
                'related_type' => $relatedType
            ] );

            $relation->save();
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Collection
     */
    public function getOptions()
    {
        return $this->options ?: $this->relatedModelType::all();
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
}