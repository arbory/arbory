<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Inputs\Input;
use Arbory\Base\Html\Html;
use Illuminate\Http\Request;

/**
 * Class Password
 * @package Arbory\Base\Admin\Form\Fields
 */
class Password extends AbstractField
{
    /**
     * @return Content
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
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

        return $content;
    }

    /**
     * @param Request $request
     * @return void
     */
    public function beforeModelSave( Request $request )
    {
        $password = $request->input( $this->getNameSpacedName() );
        $hasher = \Sentinel::getUserRepository()->getHasher();

        if( $password )
        {
            $this->getModel()->setAttribute( $this->getName(), $hasher->hash( $password ) );
        }
    }
}

