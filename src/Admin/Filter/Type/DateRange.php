<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Admin\Filter\Type;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;

/**
 * Class DateRange
 * @package Arbory\Base\Admin\Filter\Type
 */
class DateRange extends Type
{
    public function render()
    {
        return new Content([Html::div( [
            Html::div( [
                Html::h4( trans('arbory::filter.date_range.from') ),
                Html::input()
                    ->setType( 'date' )
            ] )->addClass( 'column' ),
            Html::div( [
                Html::h4( trans('arbory::filter.date_range.to') ),
                Html::input()
                    ->setType( 'date' )
            ] )->addClass( 'column' ),
        ] )->addClass( 'date-range' )]);
    }
}
