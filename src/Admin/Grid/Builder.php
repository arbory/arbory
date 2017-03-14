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
     * @var Paginator
     */
    protected $page;

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
        $tableColumns = $this->grid()->getColumns()->map( function ( Column $column )
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
        return $this->grid->getModule()->url( 'index', [
            '_order_by' => $column,
            '_order' => request( '_order' ) === 'ASC' ? 'DESC' : 'ASC',
        ] );
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
     * @return Content
     */
    protected function table()
    {
        return new Content( [
            Html::header( [
                Html::h1( trans( 'leaf.resources.all_resources' ) ),
                Html::span( trans( 'leaf.pagination.items_found', [ 'total' => $this->page->total() ] ) )
                    ->addClass( 'extras totals only-text' )
            ] ),
            Html::div(
                Html::table( [
                    Html::thead(
                        Html::tr( $this->getTableColumns()->toArray() )
                    ),
                    Html::tbody(
                        $this->grid()->getRows()->map( function ( Row $row )
                        {
                            return $row->render();
                        } )->toArray()
                    )->addClass( 'tbody' ),
                ] )->addClass( 'table' )
            )->addClass( 'body' )
        ] );
    }

    /**
     * @return \CubeSystems\Leaf\Html\Elements\Element
     */
    protected function footer()
    {
        $createButton = Link::create( $this->url( 'create' ) )
            ->asButton( 'primary' )
            ->withIcon( 'plus' )
            ->title( trans( 'leaf.resources.create_new' ) );

        $pagination = ( new Pagination( $this->page ) )->render();

        $tools = new Tools();
        $tools->getBlock( 'primary' )->push( $createButton );
        $tools->getBlock( $pagination->attributes()->get( 'class' ) )->push( $pagination->content() );

        $footer = new Footer( 'main' );
        $footer->getRows()->prepend( $tools );

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
     * @param Paginator $page
     * @return Content
     */
    public function render( Paginator $page )
    {
        $this->page = $page;

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
