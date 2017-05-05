<?php

namespace CubeSystems\Leaf\Repositories;

use CubeSystems\Leaf\Nodes\Node;

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

    /**
     * @param Node $node
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function findUnder( Node $node, string $key, $value )
    {
        $query = $this->model->newQuery()->where( $key, $value )
            ->whereBetween( $node->getLeftColumnName(), array( $node->getLeft() + 1, $node->getRight() - 1 ) )
            ->whereBetween( $node->getRightColumnName(), array( $node->getLeft() + 1, $node->getRight() - 1 ) );

        return $query->get()->first();
    }

    /***
     * @param $uri
     * @return Node|null
     */
    public function findBySlug( $uri )
    {
        $parts = explode( '/', trim( $uri, '/' ) );

        $node = null;

        foreach( $parts as $depth => $part )
        {
            $query = $this->model->newQuery()->where( 'depth', $depth )->where( 'slug', $part );

            if( $node instanceof Node )
            {
                $query->whereBetween( $node->getLeftColumnName(), array( $node->getLeft() + 1, $node->getRight() - 1 ) );
                $query->whereBetween( $node->getRightColumnName(), array( $node->getLeft() + 1, $node->getRight() - 1 ) );
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
