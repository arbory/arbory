<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Html\Elements\Content;
use CubeSystems\Leaf\Html\Elements\Inputs\Input;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Http\Request;

/**
 * Class Password
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class Password extends AbstractField
{
    /**
     * @return Content
     */
    public function render()
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

    /**
     * @param Request $request
     */
    public function afterModelSave( Request $request )
    {
        $password = $request->input( $this->getNameSpacedName() );
        $hasher = \Sentinel::getUserRepository()->getHasher();

        $this->getModel()->setAttribute( $this->getName(), $hasher->hash( $password ) );
    }
}

