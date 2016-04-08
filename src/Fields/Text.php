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
            $model = $this->getModel();

            return view( $this->getViewName(), [
                'field' => $this,
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
            ] );
        }
    }
}
