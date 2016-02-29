<?php

namespace CubeSystems\Leaf\Fields;

class Richtext extends AbstractField
{
    public function render()
    {
        if( $this->isForList() )
        {
            return view( $this->getViewName(), [
                'field' => $this,
                'url' => route( 'admin.model.edit', [ class_basename( $this->getScheme()->getResource() ), $this->getRow()->getIdentifier() ] ),
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
