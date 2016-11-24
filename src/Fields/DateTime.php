<?php

namespace CubeSystems\Leaf\Fields;

class DateTime extends AbstractField
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
                    $this->getFieldSet()->getController()->getSlug(),
                    $model->{$model->getKeyName()}
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
