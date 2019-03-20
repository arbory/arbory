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
    protected $action = '=';

    function __construct( $content = null, $column = null ){
        $this->content = $content;
        $this->column = $column;
    }

    /**
     * @return null
     */
    public function getColumn()
    {
        return $this->column;
    }

    public function render()
    {
        return new Content([
            Html::div( [
                Html::label( [
                    Html::input( $this->content )
                        ->setType( 'checkbox' )
                        ->addAttributes( [ 'value' => 1 ] )
                        ->setName( $this->column->getName() )
                ] ),
            ] )->addClass( 'checkbox' )
        ]);
    }
}
