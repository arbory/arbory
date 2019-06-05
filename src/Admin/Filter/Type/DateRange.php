<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Filter\Type;
use Arbory\Base\Html\Elements\Content;

/**
 * Class DateRange.
 */
class DateRange extends Type
{
    /**
     * @var array
     */
    protected $action = [
        '>=',
        '<=',
    ];

    /**
     * @return Content
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    public function render()
    {
        return new Content([
            Html::div([
                Html::div([
                    Html::h4(trans('arbory::filter.date.from')),
                    Html::input()
                        ->setType('date')
                        ->setName($this->column.'[min]')
                        ->addAttributes([$this->getRangeValue('min')]),
                ])->addClass('column'),
                Html::div([
                    Html::h4(trans('arbory::filter.date.to')),
                    Html::input()
                        ->setType('date')
                        ->setName($this->column.'[max]')
                        ->addAttributes([$this->getRangeValue('max')]),
                ])->addClass('column'),
            ])->addClass('date-range'),
        ]);
    }
}
