<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Html\Elements;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Support\Collection;

class Menu
{
    const COOKIE_NAME_MENU = 'menu';

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
            $name = snake_case( $item->getTitle() );
            $cookie = $this->getMenuItemCookie( $name );
            $collapsed = array_get( $cookie, 'collapsed', true );

            /** @var AbstractItem $item */
            if( !$item )
            {
                continue;
            }

            $li = Html::li()
                ->addAttributes( [ 'data-name' => $name ] );

            if ( $item->isAccessible() )
            {
                $list->append(
                    $item->render( $li )->addClass( $collapsed ? 'collapsed' : '' )
                );
            }
        }

        return $list;
    }

    /**
     * @param string $name
     * @return mixed[]
     */
    protected function getMenuItemCookie( string $name )
    {
        $cookie = (array) json_decode( array_get( $_COOKIE, self::COOKIE_NAME_MENU ) );

        return (array) array_get( $cookie, $name );
    }
}
