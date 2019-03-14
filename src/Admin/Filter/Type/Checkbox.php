<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Admin\Filter\Type;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;

/**
 * Class Checkbox
 * @package Arbory\Base\Admin\Filter\Type
 */
class Checkbox extends Type
{
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
