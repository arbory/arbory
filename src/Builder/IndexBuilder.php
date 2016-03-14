<?php

namespace CubeSystems\Leaf\Builder;

use CubeSystems\Leaf\Fields\FieldInterface;
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
     * @var IndexResult
     */
    protected $result;

    /**
     * IndexBuilder constructor.
     */
    public function __construct(  )
    {
        $this->setResult( new IndexResult );
    }

    /**
     * @return IndexResult
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param IndexResult $result
     */
    public function setResult( $result )
    {
        $this->result = $result;
    }

    /**
     * @return integer
     */
    public function getItemsPerPage()
    {
        return config( 'leaf.pagination.items_per_page' ); // TODO: Move to controller
    }

    /**
     * @param Builder $queryBuilder
     * @param FieldInterface[]|array $fields
     * @return array
     */
    protected function handleSearchParams( Builder $queryBuilder, $fields )
    {
        if( !$this->hasParameter('search') )
        {
            return $queryBuilder;
        }

        $keywords = explode( ' ', $this->getParameters('search') );

        foreach( $keywords as $string )
        {
            $queryBuilder->where( function( $query ) use ( $string, $fields )
            {
                /** @var $query Builder */
                foreach( $fields as $field )
                {
                    if( !$field->getName() )
                    {
                        continue;
                    }

                    $query->where( $field->getName(), 'LIKE', "$string%", 'OR' );
                }
            } );
        }

        return $queryBuilder;
    }

    /**
     * @param Builder|\Illuminate\Database\Query\Builder $queryBuilder
     */
    protected function handlePagination( Builder $queryBuilder )
    {
        /**
         * @var $paginator Paginator|AbstractPaginator
         */

        $paginator = $queryBuilder->paginate( $this->getItemsPerPage() );

        if( $this->hasParameter('_order_by') && $this->hasParameter('_order') )
        {
            $queryBuilder->orderBy( $this->getParameters('_order_by'), $this->getParameters('_order') );

            $paginator->addQuery( '_order_by', $this->getParameters('_order_by') );
            $paginator->addQuery( '_order', $this->getParameters('_order') );
        }

        if( $this->hasParameter('search') )
        {
            $paginator->addQuery( 'search', $this->getParameters('search') );
        }

        $this->getResult()->setPaginator( $paginator );
    }

    /**
     * @param Model $resource
     * @return Builder
     */
    protected function getQueryBuilder( $resource )
    {
        return ( new $resource )->newQuery();
    }

    /**
     * @return IndexResult
     */
    public function build()
    {
        /**
         * @var $item Model
         * @var $queryBuilder Builder|\Illuminate\Database\Query\Builder
         */

        $resource = $this->getResource();
        $fields = $this->getFieldSet()->getFields();

        $queryBuilder = $this->getQueryBuilder( $resource );

        $this->handleSearchParams( $queryBuilder, $fields );
        $this->handlePagination( $queryBuilder );

        foreach( $queryBuilder->get() as $item )
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

            $this->getResult()->addRow( $row );
        }

        return $this->getResult();
    }
}
