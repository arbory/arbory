<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Html\Elements;

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
        $ul = ( new Elements\Ul() )
            ->addClass( 'block' );

        $anyChildrenRendered = false;
        foreach( $this->getChildren() as $child )
        {
            $li = ( new Elements\Li() )
                ->setAttributeValue( 'data-name', '' );

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
                    ( new Elements\Span() )
                        ->addClass( 'trigger' )
                        ->append(
                            ( new Elements\Abr( $this->getAbbreviation() ) )
                                ->setAttributeValue( 'title', $this->getTitle() )
                        )
                        ->append(
                            ( new Elements\Span( $this->getTitle() ) )
                                ->addClass( 'name' )
                        )
                        ->append(
                            ( new Elements\Span() )
                                ->addClass( 'collapser' )
                                ->append(
                                    ( new Elements\Button() )
                                        ->setAttributeValue( 'type', 'button' )
                                        ->append(
                                            ( new Elements\I() )
                                                ->addClass( 'fa' )
                                                ->addClass( 'fa-chevron-up' )
                                        )

                                )
                        )
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
