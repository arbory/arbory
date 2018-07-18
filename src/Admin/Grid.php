<?php

namespace Arbory\Base\Admin;

use Arbory\Base\Admin\Grid\Column;
use Arbory\Base\Admin\Grid\Filter;
use Arbory\Base\Admin\Grid\FilterInterface;
use Arbory\Base\Admin\Grid\Row;
use Arbory\Base\Admin\Traits\Renderable;
use Arbory\Base\Html\Elements\Content;
use Closure;
use Illuminate\Contracts\Support\Renderable as RenderableInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class Grid
 * @package Arbory\Base\Admin
 */
class Grid
{
    use ModuleComponent;
    use Renderable;

    const FOOTER_SIDE_PRIMARY = 'primary';
    const FOOTER_SIDE_SECONDARY = 'secondary';

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
    protected $enabledDefaultTools = [ 'create', 'search' ];

    /**
     * @var array
     */
    protected $tools = [];

    /**
     * @var Collection|null
     */
    protected $items;

    /**
     * @var bool
     */
    protected $paginated = true;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @param Model $model
     * @param Closure $layout
     */
    public function __construct( Model $model )
    {
        $this->model = $model;
        $this->columns = new Collection();
        $this->rows = new Collection();

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
     * @param Closure $constructor
     * @return $this
     */
    public function setColumns( Closure $constructor ): self
    {
        $constructor($this);

        return $this;
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
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param RenderableInterface $tool
     * @param string|null $side
     * @return void
     */
    public function addTool( RenderableInterface $tool, string $side = null )
    {
        $this->tools[] = [ $tool, $side ?: self::FOOTER_SIDE_SECONDARY ];
    }

    /**
     * @return array
     */
    public function getTools()
    {
        return $this->tools;
    }

    /**
     * @param string[] $tools
     * @return Grid
     */
    public function tools( array $tools )
    {
        $this->enabledDefaultTools = $tools;

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

    public function getItems()
    {
        if ($this->items === null) {
            $this->items = $this->fetchData();
        }

        return $this->items;
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
        $this->buildRows( $this->getItems() );

        return $this->renderer->render();
    }

    /**
     * @return string[]
     */
    public function getEnabledDefaultTools(): array
    {
        return $this->enabledDefaultTools;
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
        return !empty( $this->enabledDefaultTools );
    }

    /**
     * @param string $tool
     * @return bool
     */
    public function hasTool( string $tool ): bool
    {
        return in_array( $tool, $this->enabledDefaultTools, false );
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
