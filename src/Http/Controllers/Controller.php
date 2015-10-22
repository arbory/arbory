<?php namespace CubeSystems\Leaf\Http\Controllers;

use App\Http\Requests\Request;
use CubeSystems\Leaf\Builders\IndexView;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Controller
 * @package CubeSystems\Leaf\Http\Controllers
 */
class Controller extends \Illuminate\Routing\Controller
{
    // use Routable;

    /**
     * @var Model
     */
    protected $resource;

    /**
     * @var int
     */
    protected $itemsPerPage = 20;

    /**
     * @var array
     */
    protected $indexFields = [];

    /**
     * @param IndexView $view
     * @return \Illuminate\View\View
     */
    public function index( IndexView $view )
    {
        $resource = $this->resource();

        if( \Input::has('search') )
        {
            $resource = $this->search( $resource );
        }

        $collection = $resource->paginate( $this->itemsPerPage );

        $view->setCollection( $collection );
        $view->setController( $this );

        return $view->build();
    }

    /**
     * @return Response
     */
    public function create()
    {
        $item = $this->resource()->create( \Input::all() );

        return Response::make( ( new \Leaf\Builders\EditFormBuilder( $this ) )->render() );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store( Request $request )
    {
        $item = $this->resource()->create( \Input::all() );

        return Redirect::to( $this->getIndexUrl() );
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function show( $id )
    {
        $item = $this->resource()->findOneById( $id );

        return view( static::getViewPath( 'show' ), [ 'item' => $item ] );
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit( $id )
    {
        $item = $this->resource()->findOneById( $id );

        return view( static::getViewPath( 'edit' ), [ 'item' => $item ] );
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update( Request $request, $id )
    {
        $item = $this->resource()->findOneById( $id );
        $item->store( $request->input() );

        return Redirect::to( $this->getIndexUrl() );
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy( $id )
    {
        // TODO: Confirmation

        $item = $this->resource()->findOneById( $id );

        $item->destroy();

        return Redirect::to( $this->getIndexUrl() );
    }

    /**
     * @return Model
     */
    protected function resource()
    {
        $class = $this->resource;

        return new $class;
    }

    /**
     * @param Model $resource
     * @return mixed
     */
    protected function search( Model $resource )
    {
        $text = \Input::get('search');
        $searchableFields = $this->getSearchableFields( $resource );

        return Searcher::prepare( $resource, $searchableFields, $text );
    }

    /**
     * @param Model $resource
     * @return array
     */
    protected function getSearchableFields( Model $resource )
    {
        return $this->indexFields;
    }

    /**
     * @return array
     */
    public function getIndexFields()
    {
        return (array) $this->indexFields;
    }

    /**
     * @return string
     */
    public static function getControllerName()
    {
        return snake_case( str_replace( 'Controller', '', class_basename( static::class ) ) );
    }

    /**
     * @return string
     */
    public static function getViewsPath()
    {
        return 'leaf::admin.controllers' . static::getControllerName();
    }

    /**
     * @param $view
     * @return string
     */
    public static function getViewPath( $view )
    {
        $viewPath =  static::getViewsPath() . '.' . $view;

        if( \View::exists( $viewPath ) )
        {
            return $viewPath;
        }

        return 'leaf::admin.controllers.base.' . $view;
    }

    /**
     * @return string
     */
    public function getIndexUrl()
    {
        return route('index');
    }
}
