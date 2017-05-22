<?php

namespace CubeSystems\Leaf\Admin\Grid;

use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Widgets\Pagination;
use CubeSystems\Leaf\Admin\Layout\Footer;
use CubeSystems\Leaf\Admin\Layout\Footer\Tools;
use CubeSystems\Leaf\Admin\Widgets\Link;
use CubeSystems\Leaf\Admin\Widgets\SearchField;
use CubeSystems\Leaf\Html\Elements\Content;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class Builder
 * @package CubeSystems\Leaf\Admin\Grid
 */
class Builder
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Collection|LengthAwarePaginator
     */
    protected $items;

    /**
     * Builder constructor.
     * @param Grid $grid
     */
    public function __construct( Grid $grid )
    {
        $this->grid = $grid;
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        return $this->grid;
    }

    /**
     * @return \CubeSystems\Leaf\Admin\Widgets\Breadcrumbs
     */
    protected function breadcrumbs()
    {
        return $this->grid()->getModule()->breadcrumbs();
    }

    /**
     * @return \CubeSystems\Leaf\Html\Elements\Element
     */
    protected function searchField()
    {
        return ( new SearchField( $this->url( 'index' ) ) )->render();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getTableColumns()
    {
        $tableColumns = $this->grid()->getColumns()->map( function( Column $column )
        {
            return $this->getColumnHeader( $column );
        } );

        $tableColumns->push( Html::th( Html::span( '&nbsp;' ) ) );

        return $tableColumns;
    }

    /**
     * @param Column $column
     * @return Element
     */
    protected function getColumnHeader( Column $column )
    {
        if( $column->isSortable() )
        {
            $link = Html::link( $column->getLabel() )
                ->addAttributes( [
                    'href' => $this->getColumnOrderUrl( $column->getName() )
                ] );

            if( request( '_order_by' ) === $column->getName() )
            {
                $link->append( $this->getOrderByIcon() );
            }

            return Html::th( $link );
        }

        return Html::th( Html::span( $column->getLabel() ) );
    }

    /**
     * @param $column
     * @return \CubeSystems\Leaf\Admin\Module\Route
     */
    protected function getColumnOrderUrl( $column )
    {
        return $this->grid->getModule()->url( 'index', array_filter( [
            'search' => request( 'search' ),
            '_order_by' => $column,
            '_order' => request( '_order' ) === 'ASC' ? 'DESC' : 'ASC',
        ] ) );
    }

    /**
     * @return Element
     */
    protected function getOrderByIcon()
    {
        return Html::i()
            ->addClass( 'fa' )
            ->addClass(
                ( request( '_order' ) === 'DESC' )
                    ? 'fa-sort-up'
                    : 'fa-sort-down'
            );
    }

    /**
     * @return Element
     */
    protected function tableHeader(): Element
    {
        $header = Html::header( [
            Html::h1( trans( 'leaf::resources.all_resources' ) ),
        ] );

        if( $this->grid->isPaginated() )
        {
            $header->append( Html::span( trans( 'leaf::pagination.items_found', [ 'total' => $this->items->total() ] ) )
                ->addClass( 'extras totals only-text' ) );
        }

        return $header;
    }

    /**
     * @return Content
     */
    protected function table()
    {
        return new Content( [
            $this->tableHeader(),
            Html::div(
                Html::table( [
                    Html::thead(
                        Html::tr( $this->getTableColumns()->toArray() )
                    ),
                    Html::tbody(
                        $this->grid()->getRows()->map( function( Row $row )
                        {
                            return $row->render();
                        } )->toArray()
                    )->addClass( 'tbody' ),
                ] )->addClass( 'table' )
            )->addClass( 'body' )
        ] );
    }

    /**
     * @return Link
     */
    protected function createButton()
    {
        if( !$this->grid->hasTool( 'create' ) )
        {
            return null;
        }

        return
            Link::create( $this->url( 'create' ) )
            ->asButton( 'primary' )
            ->withIcon( 'plus' )
            ->title( trans( 'leaf::resources.create_new' ) );
    }

    /**
     * @return Tools
     */
    protected function footerTools()
    {
        $tools = new Tools();

        $tools->getBlock( 'primary' )->push( $this->createButton() );

        if ( $this->grid->isPaginated() )
        {
            $pagination = ( new Pagination( $this->items ) )->render();
            $tools->getBlock( $pagination->attributes()->get( 'class' ) )->push( $pagination->content() );
        }

        return $tools;
    }

    /**
     * @return \CubeSystems\Leaf\Html\Elements\Element
     */
    protected function footer()
    {
        $footer = new Footer( 'main' );

        if ( $this->grid->hasTools() )
        {
            $footer->getRows()->prepend( $this->footerTools() );
        }

        return $footer->render();
    }

    /**
     * @param $route
     * @param array $parameters
     * @return \CubeSystems\Leaf\Admin\Module\Route
     */
    public function url( $route, $parameters = [] )
    {
        return $this->grid()->getModule()->url( $route, $parameters );
    }

    /**
     * @param Collection|Paginator $items
     * @return Content
     */
    public function render( $items )
    {
        $this->items = $items;

        return new Content( [
            Html::header( [
                $this->breadcrumbs(),
                $this->searchField(),
            ] ),
            Html::section( [
                $this->table(),
                $this->footer(),
            ] )
        ] );
    }
}
