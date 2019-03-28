<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Admin\Grid;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

class Filter implements Renderable
{
    /**
     * Filter constructor.
     * @param Grid $grid
     */
    function __construct(Grid $grid)
    {
        $this->action = $grid->getModule()->url('index');
        $this->columns = $grid->getColumns();
    }

    /**
     * @return mixed
     */
    protected function filterHeader()
    {
        return Html::div([
            Html::h2(trans('arbory::filter.sort_and_filter')),
            Button::create()
                ->type('button', 'close')
                ->withIcon('times')
                ->iconOnly(),
        ])->addClass('title-block');
    }

    /**
     * @return array
     */
    protected function addFields()
    {
        $fieldCollection = null;

        foreach ($this->columns as $column) {
            if (!$column->getHasFilter()) {
                continue;
            }

            if (!empty($column->getFilterType()->content)) {
                $content = $column->getFilterType()->content;
            } else {
                $content = null;
            }

            $fieldCollection[] = $this->addField(
                $column,
                $content
            );
        }

        return $fieldCollection;
    }

    /**
     * @param $column
     * @param $content
     * @return Content
     */
    protected function addField($column, $content)
    {
        $type = $column->getFilterType();

        if (!is_null($content)) {
            $field = new $type($content, $column);
        } else {
            $field = new $type($column);
        }
        return new Content([
            Html::div([
                Html::div([
                    Html::h3($column->getLabel()),
                    Button::create()
                        ->withIcon('minus')
                        ->iconOnly()
                        ->withoutBackground(),
                ])->addClass('accordion__heading'),
                Html::div([
                    $field,
                ])->addClass('accordion__body'),
            ])->addClass('accordion'),

        ]);
    }

    /**
     * @return Button
     */
    protected function filterButton()
    {
        return Button::create()
            ->type('submit', 'full-width')
            ->title(trans('arbory::filter.apply'));
    }

    /**
     * @return Content|string
     */
    public function render()
    {
        return new Content([
            Html::form([
                $this->filterHeader(),
                $this->addFields(),
                $this->filterButton(),
            ])->addClass('form-filter')
                ->addAttributes(['action' => $this->action])
                ->addAttributes(['method' => 'get']),
        ]);
    }
}