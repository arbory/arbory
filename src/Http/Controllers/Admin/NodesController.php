<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Form\FieldSet;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Layout;
use CubeSystems\Leaf\Admin\Traits\Crudify;
use CubeSystems\Leaf\Admin\Form\Fields\HasOne;
use CubeSystems\Leaf\Admin\Form\Fields\Hidden;
use CubeSystems\Leaf\Admin\Form\Fields\Slug;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Tools\ToolboxMenu;
use CubeSystems\Leaf\Nodes\ContentTypeDefinition;
use CubeSystems\Leaf\Nodes\Node;
use CubeSystems\Leaf\Nodes\Admin\Grid\Filter;
use CubeSystems\Leaf\Nodes\Admin\Grid\Renderer;
use CubeSystems\Leaf\Nodes\ContentTypeRegister;
use Illuminate\Container\Container;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mockery\Matcher\Closure;

class NodesController extends Controller
{
    use Crudify;

    protected $resource = Node::class;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var ContentTypeRegister
     */
    protected $contentTypeRegister;

    /**
     * @param Container $container
     * @param ContentTypeRegister $contentTypeRegister
     */
    public function __construct(
        Container $container,
        ContentTypeRegister $contentTypeRegister
    )
    {
        $this->container = $container;
        $this->contentTypeRegister = $contentTypeRegister;
    }

    /**
     * @param \CubeSystems\Leaf\Nodes\Node $node
     * @return Form
     */
    protected function form( Node $node )
    {
        $form = $this->module()->form( $node, function ( Form $form ) use ( $node )
        {
            $form->addField( new Hidden( 'parent_id' ) );
            $form->addField( new Hidden( 'content_type' ) );
            $form->addField( new Text( 'name' ) );
            $form->addField( new Slug( 'slug', $this->url( 'api', 'slug_generator' ) ) );

            $form->addField( new Text( 'meta_title' ) );
            $form->addField( new Text( 'meta_author' ) );
            $form->addField( new Text( 'meta_keywords' ) );
            $form->addField( new Text( 'meta_description' ) );

            $form->addField( new Form\Fields\Boolean( 'active' ) );
            $form->addField( new HasOne( 'content', function( FieldSet $fieldSet ) use ( $node )
            {
                $content = $node->content ?: $node->content()->getRelated();

                $class = ( new \ReflectionClass( $content ) )->getName();
                $definition = $this->contentTypeRegister->findByModelClass( $class );

                $definition->getFieldSetHandler()->call( $content, $fieldSet );
            } ) );
        } );

        $form->addEventListeners( [ 'create.after', 'update.after' ], function () use ( $form )
        {
            $this->afterSave( $form );
        } );

        return $form;
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        $grid = $this->module()->grid( $this->resource(), function ( Grid $grid )
        {
            $grid->column( 'name' );
        } );

        $grid->setFilter( new Filter( $this->resource() ) );
        $grid->setRenderer( new Renderer( $grid ) );

        return $grid;
    }

    /**
     * @param \CubeSystems\Leaf\Admin\Tools\ToolboxMenu $tools
     */
    protected function toolbox( ToolboxMenu $tools )
    {
        $node = $tools->model();

        $tools->add( 'add_child', $this->url( 'dialog', [ 'dialog' => 'content_types', 'parent_id' => $node->getKey() ] ) )->dialog();
        $tools->add( 'copy', $this->url( 'dialog', [ 'dialog' => 'copy', 'parent_id' => $node->getKey() ] ) )->dialog();
        $tools->add( 'move', $this->url( 'dialog', [ 'dialog' => 'move', 'parent_id' => $node->getKey() ] ) )->dialog();
        $tools->add( 'delete', $this->url( 'dialog', [ 'dialog' => 'confirm_delete', 'id' => $node->getKey() ] ) )->danger()->dialog();
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Layout
     */
    public function create( Request $request )
    {
        $contentType = $request->get( 'content_type' );

        if( !$this->contentTypeRegister->isValidContentType( $contentType ) )
        {
            return redirect( $this->url( 'index' ) )->withErrors( 'Undefined content type "' . $contentType . '"' );
        }

        $node = $this->resource();
        $node->setAttribute( 'content_type', $contentType );
        $node->setAttribute( 'content_id', 0 );

        if( $request->has( 'parent_id' ) )
        {
            $node->setAttribute( $node->getParentColumnName(), $request->get( 'parent_id' ) );
        }

        $layout = new Layout( function ( Layout $layout ) use ( $node )
        {
            $layout->body( $this->form( $node ) );
        } );

        $layout->bodyClass( 'controller-' . str_slug( $this->module()->name() ) . ' view-edit' );

        return $layout;
    }

    /**
     * @param Form $form
     */
    protected function afterSave( Form $form )
    {
        /**
         * @var $node Node
         */
        $node = $form->getModel();

        $parentId = $node->getAttribute( $node->getParentColumnName() );

        if( $parentId )
        {
            $parent = $node->find( $parentId );
            $node->makeChildOf( $parent );

            return;
        }

        $node->makeRoot();
    }

    /**
     * @return Node
     */
    public function resource()
    {
        $class = $this->resource;

        return new $class;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function contentTypesDialog( Request $request )
    {
        $contentTypes = $this->contentTypeRegister->getAllowedChildTypes(
            $this->resource()->findOrNew( $request->get( 'parent_id' ) )
        );

        $types = $contentTypes->map( function ( ContentTypeDefinition $definition, string $type ) use ( $request )
        {
            return [
                'title' => $definition->getName(),
                'url' => $this->url( 'create', [
                    'content_type' => $type,
                    'parent_id' => $request->get( 'parent_id' )
                ] )
            ];
        } );

        return view( 'leaf::dialogs.content_types', [ 'types' => $types ] );
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function slugGeneratorApi( Request $request )
    {
        $reservedSlugs = [];

        if( $request->has( 'parent_id' ) )
        {
            $reservedSlugs = $this->resource()->where( 'parent_id', $request->get( 'parent_id' ) )
                ->pluck( 'slug' )
                ->toArray();
        }

        $name = $request->get( 'name' );
        $slug = str_slug( $name );

        if( in_array( $slug, $reservedSlugs, true ) && $request->has( 'id' ) )
        {
            $slug = str_slug( $request->get( 'id' ) . '-' . $name );
        }

        if( in_array( $slug, $reservedSlugs, true ) )
        {
            $slug = str_slug( $name . '-' . random_int( 0, 9999 ) );
        }

        return $slug;
    }

}
