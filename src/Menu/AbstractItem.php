<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Html\Elements;

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
     * @param array $values
     * @return ItemGroupItem|ModuleItem
     */
    public static function make( array $values )
    {
        if( array_has( $values, 'items' ) )
        {
            return new ItemGroupItem( $values );
        }

        return new ModuleItem( $values, app( 'sentinel' ), app( 'leaf.modules' ) );
    }

    /**
     * @param array $values
     */
    public function __construct( array $values = [] )
    {
        $this->setTitle( array_get( $values, 'title' ) );
        $this->setAbbreviation( array_get( $values, 'abbreviation' ) );
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
     * @param Elements\Element $element
     * @return bool
     */
    abstract public function render( Elements\Element $element );
}
