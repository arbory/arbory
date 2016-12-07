<?php

namespace CubeSystems\Leaf\Fields;

class Richtext extends AbstractField
{
    public function render()
    {
        if( $this->isForList() )
        {
            $model = $this->getModel();

            return view( $this->getViewName(), [
                'field' => $this,
                'url' => route( 'admin.model.edit', [
                    $this->getController()->getSlug(),
                    $model->getKey()
                ] ),
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
