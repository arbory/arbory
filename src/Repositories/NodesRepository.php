<?php

namespace CubeSystems\Leaf\Repositories;

use CubeSystems\Leaf\Node;

/**
 * Class NodesRepository
 * @package CubeSystems\Leaf\Repositories
 */
class NodesRepository extends AbstractModelsRepository
{
    /**
     * @var string
     */
    protected $modelClass = Node::class;

    /***
     * @param $uri
     * @return Node|null
     */
    public function findRouteBySlug( $uri )
    {
        $parts = explode( '/', trim( $uri, '/' ) );

        $node = null;

        foreach( $parts as $depth => $part )
        {
            $query = $this->model->newQuery()->where( 'depth', $depth )->where( 'slug', $part );

            if( $node instanceof Node )
            {
                $query->whereBetween( 'lft', array( $node->getLeft() + 1, $node->getRight() - 1 ) );
                $query->whereBetween( 'rgt', array( $node->getLeft() + 1, $node->getRight() - 1 ) );
            }

            $nodes = $query->get();

            if( $nodes->count() !== 1 )
            {
                break;
            }

            $node = $nodes->first();
        }

        return $node;
    }
}
