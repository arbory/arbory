<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Admin\Filter\Type;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;

/**
 * Class Range
 * @package Arbory\Base\Admin\Filter\Type
 */
class Range extends Type
{
    /**
     * @var array
     */
    protected $action = ['>=', '<='];

    function __construct( $column = null ){
        $this->column = $column;
    }

    public function render()
    {
        return new Content([Html::div( [
            Html::div( [
                Html::h4( trans('arbory::filter.range.from') ),
                Html::input()
                    ->setType( 'number' )
                    ->setName( $this->column->getName() . '[min]' )
                    ->addAttributes([ 'step' => '.01'])
            ] )->addClass( 'column' ),
            Html::div( [
                Html::h4( trans('arbory::filter.range.to') ),
                Html::input()
                    ->setType( 'number' )
                    ->setName( $this->column->getName() . '[max]' )
                    ->addAttributes([ 'step' => '.01'])
            ] )->addClass( 'column' ),
        ] )->addClass( 'range' )]);
    }
}
