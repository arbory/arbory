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
    public function render()
    {
        return new Content([Html::div( [
            Html::div( [
                Html::h4( trans('arbory::filter.range.from') ),
                Html::input()
                    ->setType( 'number' )
            ] )->addClass( 'column' ),
            Html::div( [
                Html::h4( trans('arbory::filter.range.to') ),
                Html::input()
                    ->setType( 'number' )
            ] )->addClass( 'column' ),
        ] )->addClass( 'range' )]);
    }
}
