<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Admin\Filter\Fields\Checkbox;
use Arbory\Base\Admin\Filter\Fields\DateRange;
use Arbory\Base\Admin\Filter\Fields\Multiselect;
use Arbory\Base\Admin\Filter\Fields\Range;
use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Widgets\Button;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Admin\Filter\Fields\Select;

class Filter implements Renderable
{
    /**
     * @return mixed
     */
    protected function filterHeader()
    {
        return Html::div( [
            Html::h2( trans( 'arbory::filter.sort_and_filter' ) ),
            Button::create()
                ->type('button', 'close')
                ->withIcon( 'times' )
                ->iconOnly()
        ] )->addClass( 'title-block' );
    }

    /**
     * @param $type
     * @param $column
     */
    protected function addField( $type, $column, $name = '' )
    {
        if ( empty( $name ) )
        {
            $name = $column;
        }

        switch ( $type )
        {
            case 'select' :
                $field = new Select();
                break;
            case 'multiselect' :
                $field = new Multiselect();
                break;
            case 'range' :
                $field = new Range();
                break;
            case 'dateRange' :
                $field = new DateRange();
                break;
            case 'checkbox' :
                $field = new Checkbox();
                break;
        }

        return new Content( [
            Html::div( [
                Html::div( [
                    Html::h3( $name ),
                    Button::create()
                    ->withIcon( 'minus' )
                    ->iconOnly()
                    ->withoutBackground()
                ] )->addClass( 'accordion__heading' ),
                Html::div( [
                    $field,
                ] )->addClass( 'accordion__body' ),
            ] )->addClass( 'accordion' ),

        ] );
    }

    /**
     * @return Content|string
     */
    public function render()
    {
        return new Content( [
            Html::aside( [
                $this->filterHeader(),
                $this->addField('select', 'column_select', 'Select' ),
                $this->addField('multiselect', 'column_multiselect', 'Multiselect' ),
                $this->addField('range', 'column_range', 'Range integer' ),
                $this->addField('dateRange', 'column_date-range', 'Date range' ),
                $this->addField('checkbox', 'column_checkbox', 'Checkbox' )
            ] )->addClass( 'form-filter' )
        ] );


    }
}