<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Widgets\Button;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Arbory\Base\Html\Elements\Content;

class Filter implements Renderable
{
    /**
     * @return mixed
     */
    public function filterHeader()
    {
        return Html::div( [
            Html::h2( trans( 'arbory::filter.sort_and_filter' ) ),
            Button::create()
                ->type('button', 'close')
                ->iconOnly()
        ] )->addClass( 'title-block' );
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function render()
    {
        return new Content( [
            Html::aside( [
                $this->filterHeader(),
            ] )->addClass( 'form-filter' )
        ] );


    }
}