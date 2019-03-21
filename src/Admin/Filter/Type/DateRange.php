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
    /**
     * @var array
     */
    protected $action = ['>=', '<='];

    function __construct( $column = null ){
        $this->column = $column;
        $this->request = request();
    }

    public function render()
    {
        return new Content([Html::div( [
            Html::div( [
                Html::h4( trans('arbory::filter.date.from') ),
                Html::input()
                    ->setType( 'date' )
                    ->setName( $this->column->getName() . '[min]' )
                    ->addAttributes([ $this->getRangeValue('min') ])
            ] )->addClass( 'column' ),
            Html::div( [
                Html::h4( trans('arbory::filter.date.to') ),
                Html::input()
                    ->setType( 'date' )
                    ->setName( $this->column->getName() . '[max]' )
                    ->addAttributes([ $this->getRangeValue('max') ])
            ] )->addClass( 'column' ),
        ] )->addClass( 'date-range' )]);
    }
}
