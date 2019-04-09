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
    const STEP = '.01';

    /**
     * @var array
     */
    protected $action = [
        '>=',
        '<='
    ];

    /**
     * @return Content
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    public function render()
    {
        return new Content([Html::div( [
            Html::div( [
                Html::h4( trans('arbory::filter.range.from') ),
                Html::input()
                    ->setType( 'number' )
                    ->setName( $this->getColumn() . '[min]' )
                    ->addAttributes([ 'step' => self::STEP])
                    ->addAttributes([ $this->getRangeValue('min') ])
            ] )->addClass( 'column' ),
            Html::div( [
                Html::h4( trans('arbory::filter.range.to') ),
                Html::input()
                    ->setType( 'number' )
                    ->setName( $this->getColumn() . '[max]' )
                    ->addAttributes([ 'step' => self::STEP])
                    ->addAttributes([ $this->getRangeValue('max') ])
            ] )->addClass( 'column' ),
        ] )->addClass( 'range' )]);
    }
}
