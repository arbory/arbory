<?php

namespace CubeSystems\Leaf\Menu;

use Cartalyst\Sentinel\Sentinel;
use CubeSystems\Leaf\Nodes\MenuItem;
use Illuminate\Support\Collection;

/**
 * Class AbstractItem
 * @package CubeSystems\Leaf\Menu
 */
abstract class AbstractItem
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
     * @var AbstractItem|null
     */
    protected $parent;

    /**
     * @var Collection
     */
    protected $children;

    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * @var MenuItem|null
     */
    protected $model;

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
     * @param AbstractItem $parent
     * @return $this
     */
    public function setParent( AbstractItem $parent )
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAbbreviation()
    {
        if( $this->abbreviation === null )
        {
            $this->abbreviation = substr( $this->getTitle(), 0, 2 );
        }

        return $this->abbreviation;
    }

    /**
     * @return boolean
     */
    public function isVisible()
    {
        return true;
    }

    /**
     * @return AbstractItem|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @return MenuItem|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param MenuItem $model
     */
    public function setModel( MenuItem $model = null )
    {
        $this->model = $model;
    }

    /**
     * @return bool
     */
    public function hasModel()
    {
        return (bool) $this->getModel();
    }

    /**
     * @param AbstractItem $item
     * @return void
     */
    public function addChild( AbstractItem $item )
    {
        $this->children->push( $item );
    }

    /**
     * @param Collection $children
     */
    public function setChildren( Collection $children )
    {
        $this->children = $children;
    }
}
