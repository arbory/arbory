<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Elements\Content;
use CubeSystems\Leaf\Html\Elements\Div;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Elements\Inputs\Input;

class Password extends AbstractField
{
    public function __toString()
    {
        return (string) $this->renderListView();
    }

    /**
     * @param array $attributes
     * @return Content
     */
    public function render( array $attributes = [] )
    {
        if( $this->isForList() )
        {
            return $this->renderListView( $attributes );
        }
        elseif( $this->isForForm() )
        {
            $input = new Input( $this->getNameSpacedName(), '' );
            $input->setType( 'password' );
            $input->addClass( 'text' );

            $content = new Content;

            $content->push( ( new Div )
                ->append(( new Div( $input->label( $this->getLabel() ) ) )->addClass( 'label-wrap' ))
                ->append(( new Div( $input ) )->addClass( 'value' ))
                ->addClass( 'field type-password' ) );

            $content->push( ( new Div )
                ->append(( new Div( $input->label( $this->getLabel() ) ) )->addClass( 'label-wrap' ))
                ->append(( new Div( $input->setName( $this->getNameSpacedName() . '_confirmation' ) ) )->addClass( 'value' ))
                ->addClass( 'field type-password' ) );

            return $content;
        }
    }

    protected function renderListView( array $attributes = [] )
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
}
