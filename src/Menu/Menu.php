<?php

namespace CubeSystems\Leaf\Menu;

/**
 * Class Menu
 * @package CubeSystems\Leaf\Menu
 */
class Menu
{
    /**
     * @var Item[]
     */
    protected $items;

    /**
     * @param array $items
     */
    public function __construct( array $items = [ ] )
    {
        $this->addItems( $items );
    }

    /**
     * @param array $items
     */
    public function addItems( array $items = [ ] )
    {
        foreach( $items as $item )
        {
            $this->addItem( $item );
        }
    }

    /**
     * @param array $values
     */
    public function addItem( array $values = [ ] )
    {
        $this->items[] = new Item( $values );
    }

    /**
     * @return Item[]
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * @param $slug
     * @return Item|null
     */
    public function findItemBySlug( $slug )
    {
        return $this->findItemByCallback( $this->items(), function ( Item $item ) use ( $slug )
        {
            if( $item->getSlug() === $slug )
            {
                return $item;
            }

            return null;
        } );
    }

    /**
     * @param $controller
     * @return Item|null
     */
    public function findItemByController( $controller )
    {
        return $this->findItemByCallback( $this->items(), function ( Item $item ) use ( $controller )
        {
            if( $item->getController() === $controller )
            {
                return $item;
            }

            return null;
        } );
    }

    /**
     * @param Item[] $children
     * @param callable $callback
     * @return Item|null
     */
    protected function findItemByCallback( array $children, callable $callback )
    {
        foreach( $children as $child )
        {
            if( $child->hasChildren() )
            {
                $item = $this->findItemByCallback( $child->children(), $callback );
            }
            else
            {
                $item = $callback( $child );
            }

            if( $item !== null )
            {
                return $item;
            }
        }

        return null;
    }

}
