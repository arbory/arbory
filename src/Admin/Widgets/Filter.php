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
                ->type('button', 'js-filter-trigger')
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

            if (!empty($column->getFilterType()->getContent())) {
                $content = $column->getFilterType()->getContent();
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
        return new Content([
            Html::div([
                Html::div([
                    Html::h3($column->getLabel()),
                    Button::create()
                        ->withIcon('minus')
                        ->iconOnly()
                        ->withoutBackground(),
                ])->addClass('js-accordion-trigger heading'),
                Html::div([
                    self::createField($column->getFilterType(), $column, $content),
                ])->addClass('body'),
            ])->addClass('accordion'),

        ]);
    }

    /**
     * @param $type
     * @param $column
     * @param $content
     * @return mixed
     */
    protected function createField($type, $column, $content)
    {
        return is_null($content) ? new $type(null, $column) : new $type($content, $column);
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