<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\InputFieldRenderer;
use Arbory\Base\Admin\Form\Fields\Renderer\PasswordFieldRenderer;
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
    protected $renderer = PasswordFieldRenderer::class;

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

