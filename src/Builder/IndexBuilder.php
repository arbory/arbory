<?php

namespace CubeSystems\Leaf\Builder;

use CubeSystems\Leaf\Results\IndexResult;
use CubeSystems\Leaf\Results\Row;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\AbstractPaginator;

/**
 * Class IndexBuilder
 * @package CubeSystems\Leaf\Builder
 */
class IndexBuilder extends AbstractBuilder
{
    // TODO: Allow to modify builder parameters
    // TODO: Search / filter / order / pagination

    /**
     * @return integer
     */
    public function getItemsPerPage()
    {
        return config( 'leaf.pagination.items_per_page' ); // TODO: Move to controller
    }

    /**
     * @return array
     */
    public function getFilterValues()
    {
        return [ ]; // TODO
    }

    /**
     * @return IndexResult
     */
    public function build()
    {
        /**
         * @var $item Model
         * @var $eloquentBuilder Builder|\Illuminate\Database\Query\Builder
         * @var $paginator Paginator|AbstractPaginator
         */

        $results = new IndexResult();

        $resource = $this->getResource();
        $fields = $this->getScheme()->getFields();

        $eloquentBuilder = $resource::where( $this->getFilterValues() );
        $paginator = $eloquentBuilder->paginate( $this->getItemsPerPage() );

        if( \Input::has( '_order_by' ) && \Input::has( '_order' ) )
        {
            $eloquentBuilder->orderBy( \Input::get( '_order_by' ), \Input::get( '_order' ) );

            $paginator->addQuery( '_order_by', \Input::get( '_order_by' ) );
            $paginator->addQuery( '_order', \Input::get( '_order' ) );
        }

        $collection = $eloquentBuilder->get();

        $results->setPaginator( $paginator );

        foreach( $collection as $item )
        {
            $row = new Row();
            $row->setResource( $resource );
            $row->setIdentifier( $item->{$item->getKeyName()} );

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

                $row->add( $field );
            }

            $results->addRow( $row );
        }

        return $results;
    }
}
