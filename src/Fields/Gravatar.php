<?php

namespace CubeSystems\Leaf\Fields;

class Gravatar extends AbstractField
{
    /**
     * @param array $attributes
     * @return \Illuminate\View\View
     */
    public function render( array $attributes = [] )
    {
        $model = $this->getModel();

        return view( $this->getViewName(), [
            'email' => $this->getValue(),
            'attributes' => $attributes,
            'url' => route( 'admin.model.edit', [
                $this->getController()->getSlug(),
                $model->getKey()
            ] ),
        ] );
    }
}
