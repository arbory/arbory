<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Illuminate\Http\Request;

/**
 * Class Dropdown
 * @package Arbory\Base\Admin\Form\Fields
 */
class Range
{
    public function __toString()
    {
        return (string) $this->render();
    }

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
