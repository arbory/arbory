<?php

namespace CubeSystems\Leaf\Admin\Widgets;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Pagination
 * @package CubeSystems\Leaf\Admin\Widgets
 */
class Pagination implements Renderable
{
    /**
     * @var Paginator|LengthAwarePaginator
     */
    private $paginator;

    /**
     * Pagination constructor.
     * @param Paginator $paginator
     */
    public function __construct( Paginator $paginator )
    {
        $this->paginator = $paginator;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string|Element
     */
    public function render()
    {
        return Html::div( [
            $this->getPreviousPageButton(),
            $this->getPagesSelect(),
            $this->getNextPageButton(),
        ] )->addClass( 'pagination' );
    }

    /**
     * @return Element
     */
    protected function getPreviousPageButton()
    {
        $previousPage = ( $this->paginator->currentPage() > 1 )
            ? Html::link()->addAttributes( [ 'href' => $this->paginator->url( $this->paginator->currentPage() - 1 ) ] )
            : Html::button()->addAttributes( [ 'type' => 'button', 'disabled' => 'disabled' ] );

        $previousPage->append( Html::i()->addClass( 'fa fa-chevron-left' ) );
        $previousPage->addClass( 'button only-icon secondary previous' );
        $previousPage->addAttributes( [ 'title' => trans( 'leaf.pagination.previous_page' ) ] );

        return $previousPage;
    }

    /**
     * @return Element
     */
    protected function getPagesSelect()
    {
        $select = Html::select()->setName( 'page' );

        for( $i = 1; $i <= $this->paginator->lastPage(); $i++ )
        {
            $pageStart = ( $i - 1 ) * $this->paginator->perPage() + 1;
            $pageEnd = ( $this->paginator->lastPage() === $i )
                ? $this->paginator->total()
                : $i * $this->paginator->perPage();

            $option = Html::option( $pageStart . ' - ' . $pageEnd )->setValue( $i );

            if( $this->paginator->currentPage() === $i )
            {
                $option->select();
            }

            $select->append( $option );
        }

        return $select;
    }

    /**
     * @return Element
     */
    protected function getNextPageButton()
    {
        $nextPage = $this->paginator->hasMorePages()
            ? Html::link()->addAttributes( [ 'href' => $this->paginator->url( $this->paginator->currentPage() + 1 ) ] )
            : Html::button()->addAttributes( [ 'type' => 'button', 'disabled' => 'disabled' ] );

        $nextPage->append( Html::i()->addClass( 'fa fa-chevron-right' ) );
        $nextPage->addClass( 'button only-icon secondary next' );
        $nextPage->addAttributes( [ 'title' => trans( 'leaf.pagination.next_page' ) ] );

        return $nextPage;
    }
}
