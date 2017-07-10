<?php

namespace CubeSystems\Leaf\Repositories;

use CubeSystems\Leaf\Nodes\Node;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Settings;

/**
 * Class NodesRepository
 * @package CubeSystems\Leaf\Repositories
 */
class NodesRepository extends AbstractModelsRepository
{
    /**
     * @var bool
     */
    protected $onlyActiveNodes = false;

    /**
     * @var string
     */
    protected $modelClass = Node::class;

    /**
     * @return Builder
     */
    public function newQuery()
    {
        $query = parent::newQuery();

        if( $this->isQueryingOnlyActiveNodes() )
        {
            $query->where( 'active', true );
        }

        return $query;
    }

    /**
     * @param Node $node
     * @param string $key
     * @param mixed $value
     * @return Builder
     */
    public function findUnder( Node $node, string $key, $value )
    {
        $query = $this->newQuery()->where( $key, $value )
            ->whereBetween( $node->getLeftColumnName(), array( $node->getLeft() + 1, $node->getRight() - 1 ) )
            ->whereBetween( $node->getRightColumnName(), array( $node->getLeft() + 1, $node->getRight() - 1 ) );

        return $query;
    }

    /**
     * @param Node $node
     * @param string $key
     * @param mixed $value
     * @return Builder
     */
    public function findAbove( Node $node, string $key, $value )
    {
        $query = $this->newQuery()->where( $key, $value )
            ->where( $node->getLeftColumnName(), '<=', $node->getLeft() )
            ->where( $node->getRightColumnName(), '>=', $node->getRight() );

        return $query;
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
            $query = $this->newQuery()->where( 'depth', $depth )->where( 'slug', $part );

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

    /**
     * @return mixed
     */
    public function getLastUpdateTimestamp()
    {
        return Settings::get( 'nodes.last_update' );
    }

    /**
     * @param int $time
     * @return void
     */
    public function setLastUpdateTimestamp( int $time )
    {
        Settings::set( 'nodes.last_update', $time );
    }

    /**
     * @return bool
     */
    public function isQueryingOnlyActiveNodes(): bool
    {
        return $this->onlyActiveNodes;
    }

    /**
     * @param bool $onlyActiveNodes
     */
    public function setQueryOnlyActiveNodes( bool $onlyActiveNodes )
    {
        $this->onlyActiveNodes = $onlyActiveNodes;
    }
}
