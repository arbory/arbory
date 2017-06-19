<?php

namespace CubeSystems\Leaf\Admin;

use CubeSystems\Leaf\Admin\Form\Builder;
use CubeSystems\Leaf\Admin\Form\FieldSet;
use CubeSystems\Leaf\Admin\Form\Fields\FieldInterface;
use CubeSystems\Leaf\Admin\Traits\EventDispatcher;
use CubeSystems\Leaf\Html\Elements\Element;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Form
 * @package CubeSystems\Leaf\Admin
 */
class Form implements Renderable
{
    use ModuleComponent;
    use EventDispatcher;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var FieldSet
     */
    protected $fields;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var array
     */
    protected $requestClasses = [];

    /**
     * Form constructor.
     * @param Model $model
     * @param $callback
     */
    public function __construct( Model $model, $callback )
    {
        $this->model = $model;
        $this->fields = new FieldSet( $model, 'resource' );
        $this->builder = new Builder( $this );

        $callback( $this );

        $this->registerEventListeners();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @param $title
     * @return Form
     */
    public function title( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    public function getTitle()
    {
        if( $this->title === null )
        {
            $this->title = ( $this->model->getKey() )
                ? (string) $this->model
                : trans( 'leaf::resources.create_new' );
        }

        return $this->title;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return FieldSet
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * @param FieldInterface $field
     * @return FieldInterface
     */
    public function addField( FieldInterface $field )
    {
        $this->fields()->push( $field );

        return $field;
    }

    /**
     * @param Request $request
     */
    public function store( Request $request )
    {
        $this->trigger( 'validate.before', $request );

        $this->validate( $request, 'store' );

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
        $this->validate( $request, 'update' );

        $this->trigger( 'update.before', $request );

        $this->model->save();

        $this->trigger( 'update.after', $request );

        $this->model->push();
    }

    /**
     *
     */
    public function destroy()
    {
        $this->trigger( 'delete.before', $this );

        $this->model->delete();

        $this->trigger( 'delete.after', $this );
    }

    /**
     * @param $requestClass
     * @return $this
     */
    public function storeWith( $requestClass )
    {
        $this->requestClasses['store'] = $requestClass;

        return $this;
    }

    /**
     * @param $requestClass
     * @return $this
     */
    public function updateWith( $requestClass )
    {
        $this->requestClasses['update'] = $requestClass;

        return $this;
    }

    /**
     * @param Request $request
     * @param $requestType
     * @return Request|mixed
     */
    public function validate( Request $request, $requestType )
    {
        $requestClass = array_get( $this->requestClasses, $requestType );

        if( $requestClass )
        {
            return app()->make( $requestClass );
        }

        return $request;
    }

    /**
     *
     */
    protected function registerEventListeners()
    {
        $this->addEventListeners( [ 'create.before', 'update.before' ],
            function ( $request )
            {
                foreach( $this->fields() as $field )
                {
                    $field->beforeModelSave( $request );
                }
            }
        );

        $this->addEventListeners( [ 'create.after', 'update.after' ],
            function ( $request )
            {
                foreach( $this->fields() as $field )
                {
                    $field->afterModelSave( $request );
                }
            }
        );
    }

    /**
     * @param $action
     * @return $this
     */
    public function setAction( $action )
    {
        $this->builder->setAction( $action );

        return $this;
    }

    /**
     * @return \CubeSystems\Leaf\Html\Elements\Content|Element
     */
    public function render()
    {
        return $this->builder->render();
    }
}
