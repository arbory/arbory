<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Contracts\Support\Renderable;

class Filter implements Renderable
{
    /**
     * @var string
     */
    protected $action;

    /**
     * @var Grid\Column[]
     */
    protected $columns;

    /**
     * Filter constructor.
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->action = $grid->getModule()->url('index');
        $this->columns = $grid->getColumns();
    }

    /**
     * @return Element
     */
    protected function filterHeader(): Element
    {
        return Html::div([
            Html::h2(trans('arbory::filter.filter')),
            Button::create()
                ->type('button', 'js-filter-trigger')
                ->withIcon('delete_outline')
                ->iconOnly(),
        ])->addClass('title-block');
    }

    /**
     * @return array|null
     */
    protected function addFields(): ?array
    {
        $fieldCollection = null;

        foreach ($this->columns as $column) {
            if (! $column->getHasFilter()) {
                continue;
            }

            $content = $column->getFilterType()->getContent();

            $fieldCollection[] = $this->addField(
                $column,
                $content
            );
        }

        return $fieldCollection;
    }

    /**
     * @param object $column
     * @param null|object $content
     * @return Content
     */
    protected function addField($column, $content = null): Content
    {
        return new Content([
            Html::div([
                Html::div([
                    Html::h3($column->getLabel()),
                    Button::create()
                        ->withIcon('remove')
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
     * @param object $type
     * @param object $column
     * @param null|object $content
     * @return object
     */
    protected function createField($type, $column, $content = null)
    {
        return is_null($content) ? new $type(null, $column) : new $type($content, $column);
    }

    /**
     * @return Button
     */
    protected function filterButton(): Button
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
