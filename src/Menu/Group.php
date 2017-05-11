<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Html\Elements;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;

/**
 * Class ItemGroupItem
 * @package CubeSystems\Leaf\Menu
 */
class Group extends AbstractItem
{
    /**
     * @var Route
     */
    protected $route;

    /**
     * @param Route $route
     */
    public function __construct(
        Route $route
    )
    {
        $this->route = $route;
        $this->children = new Collection();
    }

    /**
     * @param Elements\Element $parentElement
     * @return bool
     */
    public function render( Elements\Element $parentElement )
    {
        $ul = Html::ul()->addClass( 'block' );

        $anyChildrenRendered = false;

        foreach( $this->getChildren() as $child )
        {
            $li = Html::li()->addAttributes( [ 'data-name' => '' ] );

            if( $child->render( $li ) )
            {
                $anyChildrenRendered = true;

                if( $child->isActive() )
                {
                    $li->addClass( 'active' );
                }

                $ul->append( $li );
            }
        }

        if( $anyChildrenRendered )
        {
            $parentElement
                ->append(
                    Html::span( [
                        Html::abbr( $this->getAbbreviation() )->addAttributes( [ 'title' => $this->getTitle() ] ),
                        Html::span( $this->getTitle() )->addClass( 'name' ),
                        Html::span( Html::button( Html::i()->addClass( 'fa fa-chevron-up' ) )->addAttributes( [ 'type' => 'button' ] ) )->addClass( 'collapser' ),
                    ] )->addClass( 'trigger ' . ( $this->isActive() ? 'active' : '' ) )
                )
                ->append( $ul );

            $result = true;
        }
        else
        {
            $result = false;
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->getChildren()->first( function( Item $item )
        {
            return $item->isActive();
        } );
    }
}
