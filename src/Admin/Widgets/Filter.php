<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Widgets\Button;
use Illuminate\Support\Collection;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Admin\Grid\Column;

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
        foreach ( $this->columns as $column ) {
            if ( $column->getFilterStatus() ) {

                if ( !empty($column->filterType->options) ){
                    $content = $column->filterType->options;
                }else{
                    $content = null;
                }

                $fieldCollection[] = $this->addField(
                    $column->getGrid()->getModel()->getTable(),
                    $column->filterType,
                    $column->getLabel(),
                    $content
                );
            }
        }

        return $fieldCollection;
    }

    /**
     * @param $table
     * @param $type
     * @param $name
     * @param $content
     * @return Content
     */
    protected function addField( $table, $type, $name, $content )
    {
        if ( $content ){
            $field = new $type($content, $table);
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