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
use Illuminate\Pagination\LengthAwarePaginator;
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
     * @var array
     */
    protected $tools = [ 'create', 'search' ];

    /**
     * @var Collection|null
     */
    protected $items;

    /**
     * @var bool
     */
    protected $paginated = true;

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
     * @return void
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
     * @param string[] $tools
     * @return Grid
     */
    public function tools( array $tools )
    {
        $this->tools = $tools;

        return $this;
    }

    /**
     * @param array|Collection $items
     * @return Grid
     */
    public function items( $items )
    {
        if( is_array( $items ) )
        {
            $items = new Collection( $items );
        }

        $this->items = $items;

        return $this;
    }

    /**
     * @param bool $paginate
     * @return Grid
     */
    public function paginate( bool $paginate = true )
    {
        $this->paginated = $paginate;

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
     * @param Collection|LengthAwarePaginator $items
     */
    protected function buildRows( $items )
    {
        if( $items instanceof LengthAwarePaginator )
        {
            $items = new Collection( $items->items() );
        }

        $this->rows = $items->map( function( $model )
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
     * @return LengthAwarePaginator|Collection
     */
    protected function fetchData()
    {
        call_user_func( $this->builder, $this );

        if( method_exists( $this->filter, 'setPaginated' ) )
        {
            $this->filter->setPaginated( $this->paginated );
        }

        return $this->filter->execute( $this->getColumns() );
    }

    /**
     * @return Content
     */
    public function render()
    {
        $result = $this->fetchData();
        $items = $this->items ?? $result;

        $this->buildRows( $items );

        return $this->renderer->render( $items );
    }

    /**
     * @return string[]
     */
    public function getTools(): array
    {
        return $this->tools;
    }

    /**
     * @return bool
     */
    public function isPaginated(): bool
    {
        return $this->paginated;
    }

    /**
     * @return bool
     */
    public function hasTools(): bool
    {
        return !empty( $this->tools );
    }

    /**
     * @param string $tool
     * @return bool
     */
    public function hasTool( string $tool ): bool
    {
        return in_array( $tool, $this->tools, false );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $items = $this->fetchData();

        $this->buildRows( $items );

        $columns = $this->columns->map( function( Column $column )
        {
            return (string) $column;
        } )->toArray();

        return $this->rows->map( function( Row $row ) use ( $columns )
        {
            return array_combine( $columns, $row->toArray() );
        } )->toArray();
    }
}
