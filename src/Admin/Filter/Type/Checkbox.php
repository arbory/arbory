<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Illuminate\Http\Request;

/**
 * Class Dropdown
 * @package Arbory\Base\Admin\Form\Fields
 */
class Checkbox
{
    public function __toString()
    {
        return (string) $this->render();
    }

    public function render()
    {
        return new Content([
            Html::div( [
                Html::label( [
                    Html::input( 'hello world' )
                        ->setType( 'checkbox' )
                ] ),
            ] )->addClass( 'checkbox' )
        ]);
    }
}
