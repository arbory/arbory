<?php

namespace CubeSystems\Leaf\Menu;

use Symfony\Component\Console\Exception\LogicException;
use CubeSystems\Leaf\Html\Elements;

/**
 * Class AbstractItem
 * @package CubeSystems\Leaf\Menu
 */
abstract class AbstractItem
{
    const TYPE_MODULE = 'module';
    const TYPE_ITEM_GROUP = 'item_group';
    const TYPE_CRUD_MODULE = 'crud_module';

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
        $type = array_get( $values, 'type', static::TYPE_MODULE );

        switch( $type )
        {
            case static::TYPE_MODULE:
                return new ModuleItem( $values, app( 'sentinel' ), app( 'leaf.modules' ) );
            case static::TYPE_CRUD_MODULE:
                return new CrudModuleItem( $values, app( 'sentinel' ), app( 'leaf.modules' ) );
            case static::TYPE_ITEM_GROUP:
                return new ItemGroupItem( $values );
            default:
                throw new LogicException( 'Menu item type "' . $type . '" is not recognized' );
        }
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
