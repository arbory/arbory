<?php

namespace CubeSystems\Leaf\Fields;

/**
 * Class Toolbox
 * @package CubeSystems\Leaf\Fields
 */
class Toolbox extends AbstractField
{
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
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view( $this->getViewName(), [
            'toolbox_url' => route( 'admin.model.action', [
                'model' => $this->getFieldSet()->getController()->getSlug(),
                'id' => $this->getRow()->getIdentifier(),
                'action' => 'toolbox'
            ] ),
        ] );
    }
}
