<?php

namespace CubeSystems\Leaf\Admin;

use Closure;
use CubeSystems\Leaf\Admin\Grid\Builder;
use CubeSystems\Leaf\Admin\Grid\Column;
use CubeSystems\Leaf\Admin\Grid\Filter;
use CubeSystems\Leaf\Admin\Grid\FilterInterface;
use CubeSystems\Leaf\Admin\Grid\Row;
use CubeSystems\Leaf\Html\Elements\Content;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Grid
 * @package CubeSystems\Leaf\Admin
 */
class Grid implements Renderable
{
    use ModuleComponent;

    /**
     * @var string
     */
    protected $keyName;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $columns;

    /**
     * @var Collection
     */
    protected $rows;

    /**
     * @var Closure
     */
    protected $builder;

    /**
     * @var Builder
     */
    protected $renderer;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * Grid constructor.
     * @param Model $model
     * @param Closure $builder
     */
    public function __construct( Model $model, Closure $builder )
    {
        $this->keyName = $model->getKeyName();
        $this->model = $model;
        $this->columns = new Collection();
        $this->rows = new Collection();
        $this->builder = $builder;
        $this->renderer = new Builder( $this );

        $this->setupFilter();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     *
     */
    protected function setupFilter()
    {
        $this->setFilter( new Filter( $this->model ) );
    }

    /**
     * @param FilterInterface $filter
     * @return Grid
     */
    public function setFilter( FilterInterface $filter )
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @param $renderer
     */
    public function setRenderer( $renderer )
    {
        $this->renderer = $renderer;
    }

    /**
     * @return Collection|Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return Collection
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param null $name
     * @param null $label
     * @return Column
     */
    public function column( $name = null, $label = null )
    {
        $column = new Column( $name, $label );
        $column->setGrid( $this );

        $this->columns->push( $column );

        if( strpos( $name, '.' ) !== false )
        {
            list( $relationName, $relationColumn ) = explode( '.', $name );

            $this->filter->withRelation( $relationName );
            $column->setRelation( $relationName, $relationColumn );
        }

        return $column;
    }

    /**
     * @param Collection $items
     */
    protected function buildRows( Collection $items )
    {
        $this->rows = $items->map( function ( $model )
        {
            return new Row( $this, $model );
        } );
    }

    /**
     * @param Closure $callback
     */
    public function filter( Closure $callback )
    {
        call_user_func( $callback, $this->filter );
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function fetchData()
    {
        call_user_func( $this->builder, $this );

        return $this->filter->execute( $this->getColumns() );
    }

    /**
     * @return Content
     */
    public function render()
    {
        $page = $this->fetchData();

        $this->buildRows( collect( $page->items() ) );

        return $this->renderer->render( $page );
    }
}
