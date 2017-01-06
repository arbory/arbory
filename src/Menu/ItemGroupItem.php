<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Html\Elements;
use CubeSystems\Leaf\Html\Html;

/**
 * Class ItemGroupItem
 * @package CubeSystems\Leaf\Menu
 */
class ItemGroupItem extends AbstractItem
{
    /**
     * @var AbstractItem[]
     */
    protected $children;

    /**
     * ItemGroupItem constructor.
     * @param array $values
     */
    public function __construct( array $values = [] )
    {
        parent::__construct( $values );

        $this->setChildren( array_get( $values, 'items', [] ) );
    }

    /**
     * @param array|null $children
     * @return $this
     */
    public function setChildren( array $children = [] )
    {
        foreach( $children as $child )
        {
            $this->children[] = AbstractItem::make( $child );
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return count( $this->children ) !== 0;
    }

    /**
     * @return AbstractItem[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Elements\Element $parentElement
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
