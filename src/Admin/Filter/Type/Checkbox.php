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
    function __construct( $content = null ){
        $this->content = $content;
    }

    public function render()
    {
        return new Content([
            Html::div( [
                Html::label( [
                    Html::input( $this->content )
                        ->setType( 'checkbox' )
                ] ),
            ] )->addClass( 'checkbox' )
        ]);
    }
}
