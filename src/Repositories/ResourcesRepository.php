<?php

namespace CubeSystems\Leaf\Repositories;

use Illuminate\Database\Eloquent\Builder;

class ResourcesRepository extends GenericRepository
{
    /**
     * ResourcesRepository constructor.
     * @param $resource
     */
    public function __construct( $class )
    {
        $this->makeModel( $class );
    }
}
