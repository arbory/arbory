<?php

namespace CubeSystems\Leaf\Fields;

/**
 * Class Text
 * @package CubeSystems\Leaf\Fields
 */
class Text extends AbstractField
{
    /**
     * @return \Illuminate\View\View
     */
    public function render()
    {
        if( $this->isForList() )
        {
            return view( $this->getViewName(), [
                'field' => $this,
                'url' => route( 'admin.model.edit', [ class_basename( $this->getScheme()->getController()->getSlug() ), $this->getRow()->getIdentifier() ] ),
            ] );
        }
        elseif( $this->isForForm() )
        {
            return view( $this->getViewName(), [
                'field' => $this,
            ] );
        }
    }
}
