<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Html\Elements;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Support\Collection;

/**
 * Class ItemGroupItem
 * @package CubeSystems\Leaf\Menu
 */
class Group extends AbstractItem
{
    public function __construct()
    {
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
                    ] )->addClass( 'trigger' )
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
}
