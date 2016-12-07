<?php

namespace CubeSystems\Leaf\Results;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Class Results
 * @package CubeSystems\Leaf\Results
 */
class IndexResult extends Collection implements ResultInterface
{
    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @return Row[]|array
     */
    public function getRows()
    {
        return $this->all();
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
