<?php

namespace CubeSystems\Leaf\Fields;
use CubeSystems\Leaf\Fields\Toolbox\Item;

/**
 * Class Toolbox
 * @package CubeSystems\Leaf\Fields
 */
class Toolbox extends AbstractField
{
    protected $items;

    public function __toString()
    {
        return '';
    }

    /**
     * @return bool
     */
    public function hasBefore()
    {
        return true;
    }

    /**
     * @return \Closure
     */
    public function getBefore()
    {
        return function ()
        {
            return null;
        };
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isSearchable()
    {
        return false;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param string $name
     * @return Item
     */
    public function addItem( $name )
    {
        $item = new Item( $name );
        $item->setToolbox( $this );

        $this->items[] = $item;

        return $item;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $model = $this->getModel();

        $toolboxUrl = route( 'admin.model.dialog', [
            'model' => $this->getController()->getSlug(),
            'dialog' => 'toolbox',
            'name' => $this->getName(),
            'id' => $model->getKey(),
        ] );

        ob_start();
        ?>
        <div class="toolbox" data-url="<?=$toolboxUrl;?>">
            <button class="button trigger only-icon" type="button" title="Tools"><i class="fa fa-ellipsis-v"></i></button>
            <menu class="toolbox-items" type="toolbar"><i class="fa fa-caret-up"></i>
                <ul></ul>
            </menu>
        </div>
        <?php

        return ob_get_clean();

        $model = $this->getModel();





        return view( $this->getViewName(), [
            'toolbox_url' => route( 'admin.model.dialog', [
                'model' => $this->getController()->getSlug(),
                'dialog' => 'toolbox',
                'name' => $this->getName(),
                'id' => $model->getKey(),
            ] ),
        ] );
    }

    public function renderMenu()
    {
        return view( $this->getViewName() . '_menu', [
            'items' => $this->getItems(),
        ] );
    }
}
