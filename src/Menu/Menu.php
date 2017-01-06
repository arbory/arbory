<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Html\Elements;
use CubeSystems\Leaf\Services\ModuleRegistry;

/**
 * Class Menu
 * @package CubeSystems\Leaf\Menu
 */
class Menu
{
    /**
     * @var AbstractItem[]
     */
    protected $items;

    /**
     * @param array $itemsConfigurationArray
     */
    public function __construct( array $itemsConfigurationArray = [] )
    {
        $this->addItems( $itemsConfigurationArray );
    }

    /**
     * @param array $itemsConfigurationArray
     */
    public function addItems( array $itemsConfigurationArray = [] )
    {
        foreach( $itemsConfigurationArray as $itemConfigurationArray )
        {
            $this->addItem( $itemConfigurationArray );
        }
    }

    /**
     * @param array $itemConfigurationArray
     */
    public function addItem( array $itemConfigurationArray = [] )
    {
        $this->items[] = AbstractItem::make( $itemConfigurationArray );
    }

    /**
     * @return AbstractItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param $controllerClass
     * @return AbstractItem|null
     */
    public function findItemByController( $controllerClass )
    {
        $result = null;
        foreach( $this->getItems() as $item )
        {
            if( $item instanceof ModuleItem )
            {
                $moduleName = $item->getModuleName();

                $modulesRegistry = app( 'leaf.modules' );
                /* @var $modulesRegistry ModuleRegistry */

                $module = $modulesRegistry->findModuleByName( $moduleName );

                if( $module && $module->getControllerClass() == $controllerClass )
                {
                    $result = $item;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * @param AbstractItem[] $children
     * @param callable $callback
     * @return AbstractItem|null
     */
    protected function findItemByCallback( array $children, callable $callback )
    {
        foreach( $children as $child )
        {
            if( $child->hasChildren() )
            {
                $item = $this->findItemByCallback( $child->children(), $callback );
            }
            else
            {
                $item = $callback( $child );
            }

            if( $item !== null )
            {
                return $item;
            }
        }

        return null;
    }

    /**
     * @return Elements\Element
     */
    public function render()
    {
        $ul = new Elements\Ul();
        $ul->addClass( 'block' );

        foreach( $this->getItems() as $item )
        {
            $li = ( new Elements\Li() )
                ->setAttributeValue( 'data-name', '' );

            if( $item->render( $li ) )
            {
                $ul->append( $li );
            }
        }

        return $ul;
    }
}
