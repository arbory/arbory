<?php

namespace CubeSystems\Leaf\Fields;

class Slug extends AbstractField
{
    /**
     * @param array $attributes
     * @return \Illuminate\View\View
     */
    public function render( array $attributes = [] )
    {
        if( $this->isForForm() )
        {
            return view( $this->getViewName(), [
                'field' => $this,
                'attributes' => $attributes,
                'base_url' => url('/'),
                'uri' => $this->getModel()->getUri(),
                'slug_generator_url' => $this->getSlugGeneratorUrl()
            ] );
        }
    }

    private function getSlugGeneratorUrl()
    {
        $model = $this->getModel();

        $params = [
            'model' => $this->getController()->getSlug(),
            'api' => 'slug_generator',
        ];

        if( $model )
        {
            $params['id'] = $model->getId();
            $params['parent_id'] = $model->getParentId();
        }

        return route( 'admin.model.api', array_filter( $params ) );
    }
}
