<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Widgets\Button;
use Illuminate\Support\Collection;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Admin\Grid;

class Filter
{
    /**
     * Filter constructor.
     * @param $columns
     */
    function __construct( $columns ) {
        $this->columns = $columns;
    }

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
     * @return array
     */
    protected function addFields()
    {
        $fieldCollection = [];

        foreach ( $this->columns as $column ) {
            if ( $column->getFilterStatus() ) {
                $type = $column->filterType;

                if ( !empty($type->options) ){
                    $content = $column->filterType->options;
                }else{
                    $content = null;
                }

                $name = $column->getLabel();

                $fieldCollection[] = $this->addField( $column->filterType, $name, $content );
            }
        }

        return $fieldCollection;
    }

    /**
     * @param $type
     * @param $column
     */
    protected function addField( $type, $name, $content )
    {
        if ( $content ){
            $field = new $type($content);
        }else{
            $field = new $type();
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
                $this->addFields(),
            ] )->addClass( 'form-filter' )
        ] );
    }
}