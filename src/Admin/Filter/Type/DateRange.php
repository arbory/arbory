<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Form\Fields\DateTime;
use Illuminate\Http\Request;

/**
 * Class Dropdown
 * @package Arbory\Base\Admin\Form\Fields
 */
class DateRange
{
    public function __toString()
    {
        return (string) $this->render();
    }

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
