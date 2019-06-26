<?php


namespace Arbory\Base\Admin\Filter;


use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Closure;

class Renderer
{
    /**
     * @var FilterBuilder
     */
    protected $builder;

    /**
     * @var string
     */
    protected $action;

    /**
     * @return FilterBuilder
     */
    public function getBuilder(): FilterBuilder
    {
        return $this->builder;
    }

    /**
     * @param FilterBuilder $builder
     * @return Renderer
     */
    public function setBuilder(FilterBuilder $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * @return Element
     */
    protected function filterHeader(): Element
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
     * @return Button
     */
    protected function filterButton(): Button
    {
        return Button::create()
            ->type('submit', 'full-width')
            ->title(trans('arbory::filter.apply'));
    }

    /**
     * @return Button
     */
    protected function resetButton(): Button
    {
        return Button::create()
            ->type('reset', 'full-width reset secondary')
            ->title(trans('arbory::filter.reset'));
    }

    /**
     * @return Content
     */
    protected function renderFilters(): Content
    {
        return (new Content($this->builder->getFilters()))
            ->map(Closure::fromCallable([$this, 'renderFilter']));
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return Renderer
     */
    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @param FilterItem $filterItem
     * @return Element
     */
    protected function renderFilter(FilterItem $filterItem)
    {
        return Html::div([
            Html::div([
                Html::h3($filterItem->getTitle()),
                Button::create()
                    ->withIcon('minus')
                    ->iconOnly()
                    ->withoutBackground(),
            ])->addClass('js-accordion-trigger heading'),
            Html::div([
                $filterItem->getType()->render($filterItem)
            ])->addClass('body'),
        ])->addClass('accordion');
    }

    /**
     * @return Content|string
     */
    public function render()
    {
        return new Content([
            Html::form([
                $this->filterHeader(),
                $this->renderFilters(),
                $this->filterButton(),
                $this->resetButton(),
            ])->addClass('form-filter')
                ->addAttributes(['action' => $this->action])
                ->addAttributes(['method' => 'get']),
        ]);
    }
}