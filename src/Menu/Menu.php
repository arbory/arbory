<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Html\Elements;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Support\Collection;

class Menu
{
    /**
     * @var Collection
     */
    protected $items;

    /**
     * @param Collection|null $items
     */
    public function __construct( Collection $items = null )
    {
        $this->items = $items ?: new Collection();
    }

    /**
     * @param AbstractItem $item
     * @return void
     */
    public function addItem( AbstractItem $item )
    {
        $this->items->push( $item );
    }

    /**
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return Elements\Element
     */
    public function render()
    {
        $list = Html::ul()->addClass( 'block' );

        foreach( $this->getItems() as $item )
        {
            if( !$item )
            {
                continue;
            }

            $li = Html::li()
                ->addAttributes( [ 'data-name' => '' ] );

            if( $item->render( $li ) )
            {
                $list->append( $li );
            }
        }

        return $list;
    }

    /**
     * @param int $id
     * @return void
     */
    public function removeItemByModelId( int $id )
    {
        $this->items = $this->items->reject( function( AbstractItem $item ) use ( $id )
        {
            if( !$item->hasModel() )
            {
                return false;
            }

            return $item->getModel()->getId() === $id;
        } );
    }

    /**
     * @param int $id
     * @return AbstractItem|null
     */
    public function findItemByModelId( int $id )
    {
        return $this->getItems()->filter( function( AbstractItem $item ) use ( $id )
        {
            if( !$item->hasModel() )
            {
                return false;
            }

            return $item->getModel()->getId() === $id;
        } )->first();
    }
}
