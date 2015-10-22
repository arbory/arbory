<?php

namespace CubeSystems\Leaf\Builders;

use CubeSystems\Leaf\Http\Controllers\Controller;

class IndexView implements InterfaceView
{
    /**
     * @var Controller
     */
    protected $controller;

    /**
     * @var \Traversable
     */
    protected $collection;

    /**
     * @var Table
     */
    protected $table;

    /**
     * @var array
     */
    protected $controls;

    /**
     * @param Controller $controller
     */
    public function setController( $controller )
    {
        $this->controller = $controller;
    }

    /**
     * @param \Traversable $collection
     */
    public function setCollection( $collection )
    {
        $this->collection = $collection;
    }

    /**
     * @return Controller
     */
    public function controller()
    {
        return $this->controller;
    }

    /**
     * @return Breadcrumbs
     */
    public function breadcrumbs()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add( 'home', 'http://example.com' );
        $breadcrumbs->add( 'title', 'http://example.com' );

        return $breadcrumbs;
    }

    /**
     * @return Table
     */
    public function table()
    {
        if( $this->table === null )
        {
            $this->table = new Table( $this, $this->collection, $this->controller->getIndexFields() );
        }

        return $this->table;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function build()
    {
        return view( $this->controller->getViewPath( 'index' ), [
            'controller' => $this->controller(),
            'collection' => $this->collection,
            'breadcrumbs' => $this->breadcrumbs(),
            'table' => $this->table(),
            'controls' => $this->controls,
        ] );
    }
}
