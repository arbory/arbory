<?php

namespace CubeSystems\Leaf\Menu;

/**
 * Class Item
 * @package CubeSystems\Leaf\Menu
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
    protected $abbreviation;

    /**
     * @var string
     */
    protected $routeName;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $controller;

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
        $this->setTitle( array_get( $values, 'title' ) );
        $this->setRouteName( array_get( $values, 'route' ) );
        $this->setController( array_get( $values, 'controller' ) );
        $this->setSlug( array_get( $values, 'slug' ) );
        $this->setAbbreviation( array_get( $values, 'abbreviation' ) );
        $this->setChildren( array_get( $values, 'items', [ ] ) );
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $abbreviation
     * @return $this
     */
    public function setAbbreviation( $abbreviation )
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * @param string $routeName
     * @return $this
     */
    public function setRouteName( $routeName )
    {
        $this->routeName = $routeName;

        return $this;
    }

    /**
     * @param $slug
     * @return $this
     */
    public function setSlug( $slug )
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @param string $controller
     * @return $this
     */
    public function setController( $controller )
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @param Item $parent
     * @return $this
     */
    public function setParent( Item $parent )
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @param array|null $children
     * @return $this
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
                $item->setRouteName( $child );
            }

            $this->children[] = $item;
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
     * @return string
     */
    public function getTitle()
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
    public function getAbbreviation()
    {
        if( $this->abbreviation === null )
        {
            return substr( $this->getTitle(), 0, 2 );
        }

        return $this->abbreviation;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        if( $this->slug === null )
        {
            return str_slug( $this->getTitle() );
        }

        return $this->slug;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        if( $this->routeName === null )
        {
            return 'admin.model.index';
        }

        return $this->routeName;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return route( $this->getRouteName(), [ 'model' => $this->getSlug() ] );
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return Item[]
     */
    public function children()
    {
        return $this->children;
    }

}
