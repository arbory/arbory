<?php

namespace CubeSystems\Leaf\Results;

use Illuminate\Contracts\Pagination\Paginator;

/**
 * Class Results
 * @package CubeSystems\Leaf\Results
 */
class IndexResult implements ResultInterface
{
    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var Row[]|array
     */
    protected $rows = [ ];

    /**
     * @param Row $row
     */
    public function addRow( Row $row )
    {
        $this->rows[] = $row;
    }

    /**
     * @return Row[]|array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param Paginator $paginator
     * @return $this
     */
    public function setPaginator( Paginator $paginator )
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * @return Paginator
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

}
