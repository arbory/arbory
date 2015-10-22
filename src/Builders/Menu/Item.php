<?php

namespace CubeSystems\Leaf\Builders\Menu;

/**
 * Class Item
 * @package CubeSystems\Leaf\Builders\Menu
 */
class Item
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var string
     */
    protected $icon;

    /**
     * @var Item[]
     */
    protected $children;

    /**
     * @var Item|null
     */
    protected $parent;

    /**
     * @param array $values
     */
    public function __construct( array $values = [ ] )
    {
        if( !$values )
        {
            return;
        }

        $this->setTitle( array_get( $values, 'title' ) );
        $this->setRoute( array_get( $values, 'route' ) );
        $this->setIcon( array_get( $values, 'icon' ) );
        $this->setChildren( array_get( $values, 'items', [ ] ) );
    }

    /**
     * @param string $title
     */
    public function setTitle( $title )
    {
        $this->title = $title;
    }

    /**
     * @param string $route
     */
    public function setRoute( $route )
    {
        $this->route = $route;
    }

    /**
     * @param string $icon
     */
    public function setIcon( $icon )
    {
        $this->icon = $icon;
    }

    /**
     * @param Item $parent
     */
    public function setParent( Item $parent )
    {
        $this->parent = $parent;
    }

    /**
     * @param array|null $children
     */
    public function setChildren( array $children = [ ] )
    {
        foreach( $children as $child )
        {
            if( is_array( $child ) )
            {
                $item = new static( $child );
            }
            else
            {
                $item = new static;
                $item->setRoute( $child );
            }

            $this->children[] = $item;
        }
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !empty( $this->children );
    }

    /**
     * @return string
     */
    public function title()
    {
        if( $this->title )
        {
            return $this->title;
        }
        elseif( $this->route )
        {
            return title_case( $this->route );
        }

        return 'Unknown menu item';
    }

    /**
     * @return string
     */
    public function url()
    {
        return route( $this->route );
    }

    /**
     * @return string
     */
    public function icon()
    {
        if( !$this->icon )
        {
            return '';
        }

        return 'fa fa-' . $this->icon;
    }

    /**
     * @return Item[]
     */
    public function children()
    {
        return $this->children;
    }

}
