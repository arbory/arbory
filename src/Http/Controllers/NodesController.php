<?php

namespace CubeSystems\Leaf\Http\Controllers;

use CubeSystems\Leaf\Builder\FormBuilder;
use CubeSystems\Leaf\Fields\HasOne;
use CubeSystems\Leaf\Fields\Hidden;
use CubeSystems\Leaf\Fields\Text;
use CubeSystems\Leaf\Fields\Toolbox;
use CubeSystems\Leaf\FieldSet;
use CubeSystems\Leaf\Node;
use CubeSystems\Leaf\Pages\PageInterface;
use CubeSystems\Leaf\Results\Row;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use ReflectionClass;

class NodesController extends AdminController
{
    const TOOLBOX_TOOLS = 'tools';

    /**
     * @var Node
     */
    protected $resource = Node::class;

    /**
     * @var string
     */
    protected $indexView = 'leaf::controllers.nodes.index';

    /**
     * @param FieldSet $fieldSet
     */
    public function indexFields( FieldSet $fieldSet )
    {
        $fieldSet->add( new Text( 'name' ) );
        $fieldSet->add( new Toolbox( self::TOOLBOX_TOOLS ) );
    }

    /**
     * @param FieldSet $fieldSet
     */
    public function formFields( FieldSet $fieldSet )
    {
        $fieldSet->add( new Hidden( 'parent_id' ) );
        $fieldSet->add( new Hidden( 'content_type' ) );
        $fieldSet->add( new Text( 'name' ) );
        $fieldSet->add( new Text( 'slug' ) );
    }

    /**
     * @param FieldSet $fieldSet
     * @param Node $node
     */
    protected function contentFields( FieldSet $fieldSet, Node $node )
    {
        $fieldSet->add( new HasOne( 'content', function ( FieldSet $fieldSet ) use ( $node )
        {
            foreach( $node->content()->getRelated()->getFillable() as $fieldName )
            {
                $fieldSet->add( new Text( $fieldName ) );
            }
        } ) );
    }

    /**
     * @param integer|null $resourceId
     * @return FormBuilder
     */
    public function getFormBuilder( $resourceId = null )
    {
        $model = $this->repository->findOrNew( $resourceId );

        if( !$resourceId )
        {
            $model->content_id = 0;
            $model->content_type = $this->app['request']->get( 'content_type' );

            if( $this->app['request']->has( 'parent_id' ) )
            {
                $model->{$model->getParentColumnName()} = $this->app['request']->get( 'parent_id' );
            }
        }

        $builder = new FormBuilder( $model );
        $fieldSet = new FieldSet( $this->getResource(), $this );

        if( method_exists( $this, 'formFields' ) )
        {
            $this->formFields( $fieldSet );
        }

        $this->contentFields( $fieldSet, $model );

        $builder
            ->setFieldSet( $fieldSet )
            ->setController( $this );

        return $builder;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create()
    {
        $class = $this->app['request']->get( 'content_type' );

        // TODO: Move this check to pages repository
        if( !class_exists( $class ) || !( new ReflectionClass( $class ) )->implementsInterface( PageInterface::class ) )
        {
            \Session::flash( 'message.error', 'Undefined content type "' . $class . '"' );

            return \Redirect::to( route( 'admin.model.index', $this->getSlug() ) );
        }

        return parent::create();
    }

    /**
     * @param Model $model
     * @param array $input
     */
    public function afterCreate( Model $model, array $input = [ ] )
    {
        $this->afterSave( $model, $input );
    }

    /**
     * @param Model $model
     * @param array $input
     */
    public function afterUpdate( Model $model, array $input = [ ] )
    {
        $this->afterSave( $model, $input );
    }

    /**
     * @param Model $model
     * @param array $input
     */
    public function afterSave( Model $model, array $input = [ ] )
    {
        /**
         * @var $model \Baum\Node
         */

        $parentId = array_get( $input, 'parent_id' );

        if( $parentId )
        {
            $parent = $model->find( $parentId );
            $model->makeChildOf( $parent );
        }
        else
        {
            $model->makeRoot();
        }
    }


    // TODO: move object in tree


    // TODO: validation rules


    /**
     * @return FieldSet
     */
    public function getIndexFieldSet()
    {
        $fieldSet = new FieldSet( $this->getResource(), $this );

        $this->indexFields( $fieldSet );

        return $fieldSet;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( Request $request )
    {
        return view( $this->getIndexView(), [
            'controller' => $this,
            'rows' => $this->buildTree(
                $this->repository->all()->toHierarchy(),
                $this->getIndexFieldSet()
            ),
        ] );
    }

    /**
     * @param Collection $items
     * @param FieldSet $fieldSet
     * @return Collection
     */
    public function buildTree( Collection $items, FieldSet $fieldSet )
    {
        $results = new Collection();
        $fields = $fieldSet->getFields();

        foreach( $items as $item )
        {
            $row = new Row();
            $row->setIdentifier( $item->{$item->getKeyName()} );
            $row->setModel( $item );

            foreach( $fields as $field )
            {
                $field = clone $field;
                $field->setListContext();

                if( $field->hasBefore() )
                {
                    $before = $field->getBefore();
                    $value = $before( $item );
                }
                else
                {
                    $value = $item->{$field->getName()};
                }

                $field->setValue( $value );
                $field->setModel( $item );

                $row->addField( $field );
            }

            if( $item->children->count() )
            {
                $children = $this->buildTree( $item->children, $fieldSet );

                $row->setChildRows( $children );
            }

            $results->add( $row );
        }

        return $results;
    }

    // TODO: Move this to pages repository
    // TODO: Get all available pages
    // TODO: Get allowed children for node / level
    /**
     * @param null $nodeId
     * @return mixed
     */
    protected function getAllowedContentTypes( $nodeId = null )
    {
        $contentTypes = config( 'leaf.content_types' );

        if( $nodeId )
        {
            $node = $this->repository->find( $nodeId );

            if( $node && method_exists( $node, 'getAllowedChildContentTypes' ) )
            {
                $contentTypes = $node->getAllowedChildContentTypes();
            }
        }

        return $contentTypes;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function contentTypesDialog()
    {
        $types = [ ];

        $parentId = $this->app['request']->get( 'parent_id' );

        foreach( $this->getAllowedContentTypes( $parentId ) as $contentType )
        {
            $types[] = [
                'url' => route( 'admin.model.create', [
                    'model' => $this->getSlug(),
                    'content_type' => $contentType,
                    'parent_id' => $parentId
                ] ),
                'title' => preg_replace( '/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', class_basename( $contentType ) ),
            ];
        }

        return view( 'leaf::dialogs.content_types', [ 'types' => $types ] );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    protected function toolboxDialog()
    {
        $name = $this->app['request']->get( 'name' );

        $toolbox = $this->getIndexFieldSet()->getField( $name );

        if( !$toolbox instanceof Toolbox )
        {
            return null;
        }

        switch( $name )
        {
            case self::TOOLBOX_TOOLS:

                $node = Node::find( $this->app['request']->get( 'id' ) );

                $toolbox->setModel( $node );
                $toolbox->setController( $this );
                $toolbox->addItem( 'add_child' )
                    ->setUrl( route( 'admin.model.dialog', [
                        'dialog' => 'content_types',
                        'model' => $this->getSlug(),
                        'parent_id' => $node->id,
                    ] ) )
                    ->setTitle( 'add_child' );
                $toolbox->addItem( 'delete' )
                    ->setUrl( route( 'admin.model.dialog', [
                        'dialog' => 'confirm_delete',
                        'model' => $this->getSlug(),
                        'id' => $node->id,
                    ] ) )
                    ->setTitle( 'delete' );

                break;
        }

        return $toolbox->renderMenu();
    }

}
