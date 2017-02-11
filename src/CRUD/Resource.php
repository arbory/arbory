<?php

namespace CubeSystems\Leaf\CRUD;

use Closure;
use CubeSystems\Leaf\Fields\FieldInterface;
use CubeSystems\Leaf\FieldSet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Resource
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var ResourceFieldSet|FieldInterface[]
     */
    protected $resourceFieldSet;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * Resource constructor.
     * @param Model $model
     * @param FieldSet $fieldSet
     * @param string $namespace
     */
    public function __construct( Model $model, FieldSet $fieldSet, $namespace = 'resource' )
    {
        $this->model = $model;
        $this->identifier = $model->getKey();
        $this->namespace = $namespace;

        $this->buildResourceFieldSet( $fieldSet, $namespace );
        $this->registerEventListeners();
    }

    /**
     * @param FieldSet $fieldSet
     * @param $namespace
     */
    protected function buildResourceFieldSet( FieldSet $fieldSet, $namespace )
    {
        $this->resourceFieldSet = new ResourceFieldSet();

        foreach( $fieldSet->getFields() as $field )
        {
            $item = clone $field;
//            $item->setFieldSet( $this->resourceFieldSet );
            $item->setResource( $this );
//            $item->setModel( $this->model );

            $this->resourceFieldSet->push( $item );
        }
    }

    /**
     *
     */
    protected function registerEventListeners()
    {
        $this->addEventListeners( [ 'create.before', 'update.before' ],
            function ( Resource $resource, $request )
            {
                foreach( $resource->getFields()->all() as $field )
                {
                    $field->beforeModelSave( $request );
                }
            }
        );

        $this->addEventListeners( [ 'create.after', 'update.after' ],
            function ( Resource $resource, $request )
            {
                foreach( $resource->getFields()->all() as $field )
                {
                    $field->afterModelSave( $request );
                }
            }
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->model;
    }

    /**
     * @return ResourceFieldSet|FieldInterface[]
     */
    public function getFields()
    {
        return $this->resourceFieldSet;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->model->getKey();
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getNamespace(  )
    {
        return $this->namespace;
    }

    /**
     * @param Request $request
     */
    public function store( Request $request )
    {
        $this->trigger( 'create.before', $request );

        $this->model->save();

        $this->trigger( 'create.after', $request );

        $this->model->push();
    }

    /**
     * @param Request $request
     */
    public function update( Request $request )
    {
        $this->trigger( 'update.before', $request );

        $this->model->push();

        $this->trigger( 'update.after', $request );

        $this->model->push();
    }

    /**
     *
     */
    public function destroy()
    {
        $this->trigger( 'delete.before' );

        $this->model->delete();

        $this->trigger( 'delete.after' );
    }

    /**
     * @param $event
     * @param array ...$parameters
     */
    protected function trigger( $event, ...$parameters )
    {
        foreach( $this->getEventListeners( $event ) as $listener )
        {
            $listener( $this, ...$parameters );
        }
    }

    /**
     * @param $events
     * @param Closure $callback
     */
    public function addEventListeners( $events, Closure $callback )
    {
        foreach( (array) $events as $event )
        {
            $this->addEventListener( $event, $callback );
        }
    }

    /**
     * @param $event
     * @param Closure $callback
     */
    public function addEventListener( $event, Closure $callback )
    {
        $this->eventListeners[$event][] = $callback;
    }

    /**
     * @param $event
     * @return array
     */
    public function getEventListeners( $event )
    {
        return (array) $this->eventListeners[$event];
    }
}
