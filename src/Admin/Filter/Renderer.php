<?php

namespace Arbory\Base\Admin\Filter;

use Closure;
use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;

class Renderer
{
    /**
     * @var FilterManager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $action;

    /**
     * @return FilterManager
     */
    public function getManager(): FilterManager
    {
        return $this->manager;
    }

    /**
     * @param FilterManager $manager
     * @return Renderer
     */
    public function setManager(FilterManager $manager): self
    {
        $this->manager = $manager;

        return $this;
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
     * @return Button
     */
    protected function filterButton(): Button
    {
        return Button::create()
            ->type('submit', 'full-width')
            ->title(trans('arbory::filter.apply'));
    }

    /**
     * @return Link
     */
    protected function saveButton(): Link
    {
        $linkUrl = $this->getManager()->getModule()->url('dialog', ['dialog' => 'save_filter']);

        return Link::create($linkUrl)
            ->asButton('full-width')
            ->asAjaxbox()
            ->title(trans('arbory::filter.save_as'));
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
        return (new Content($this->manager->getFilters()))
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
        $isOpen = $filterItem->isOpen();

        if ($isOpen === null) {
            $isOpen = ! $filterItem->getType()->isEmpty();
        }

        return Html::div([
            Html::div([
                Html::h3($filterItem->getTitle()),
                Button::create()
                    ->withIcon($isOpen ? 'add' : 'remove')
                    ->iconOnly()
                    ->withoutBackground(),
            ])->addClass('js-accordion-trigger heading'),
            Html::div([
                $filterItem->getType()->render($filterItem),
            ])->addClass('body'.(! $isOpen ? ' hidden' : '')),
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
                $this->saveButton(),
                $this->resetButton(),
            ])->addClass('form-filter')
                ->addAttributes(['id' => 'grid-filter'])
                ->addAttributes(['action' => $this->action])
                ->addAttributes(['method' => 'get']),
        ]);
    }
}
