<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Elements\Content;
use CubeSystems\Leaf\Html\Elements\Inputs\Input;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Database\Eloquent\Model;

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
            $input = new Input;
            $input->setName( $this->getNameSpacedName() );
            $input->setType( 'password' );
            $input->addClass( 'text' );

            $content = new Content;

            $content->push( Html::div()
                ->append( Html::div( $input->getLabel( $this->getLabel() ) )->addClass( 'label-wrap' ) )
                ->append( Html::div( $input )->addClass( 'value' ) )
                ->addClass( 'field type-password' ) );


            $confirmationInput = new Input;
            $confirmationInput->setName( $this->getNameSpacedName() . '_confirmation' );
            $confirmationInput->setType( 'password' );
            $confirmationInput->addClass( 'text' );

            $content->push( Html::div()
                ->append( Html::div( $confirmationInput->getLabel( $this->getLabel() ) )->addClass( 'label-wrap' ) )
                ->append( Html::div( $confirmationInput )->addClass( 'value' ) )
                ->addClass( 'field type-password' ) );

            return $content;
        }
    }

    protected function renderListView( array $attributes = [] )
    {
        return '********';
    }

    public function afterModelSave( Model $model, array $input = [] )
    {
        $password = array_get( $input, $this->getName() );
        $hasher = \Sentinel::getUserRepository()->getHasher();

        $model->setAttribute( $this->getName(), $hasher->hash($password) );
    }
}

