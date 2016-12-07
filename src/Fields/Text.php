<?php

namespace CubeSystems\Leaf\Fields;

/**
 * Class Text
 * @package CubeSystems\Leaf\Fields
 */
class Text extends AbstractField
{
    /**
     * @param array $attributes
     * @return \Illuminate\View\View
     */
    public function render( array $attributes = [] )
    {
        if( $this->isForList() )
        {
            $model = $this->getModel();

            return view( $this->getViewName(), [
                'field' => $this,
                'attributes' => $attributes,
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
                'attributes' => $attributes,
            ] );
        }
    }
}
