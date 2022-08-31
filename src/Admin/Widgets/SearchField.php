<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class SearchField.
 */
class SearchField implements Renderable
{
    /**
     * @var string
     */
    protected string $name = 'search';

    /**
     * SearchField constructor.
     *
     * @param $action
     */
    public function __construct(protected $action)
    {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->render();
    }

    /**
     * @param $name
     * @return SearchField
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $content
     * @return Element
     */
    protected function createForm($content)
    {
        return Html::form($content)
            ->addClass('search has-text-search')
            ->addAttributes(['action' => $this->action]);
    }

    public function render(): Element
    {
        $searchInput = Html::input()
            ->setName($this->name)
            ->setType('search')
            ->addClass('text')
            ->addAttributes(['autofocus' => 'autofocus'])
            ->setValue(request()->get($this->name));

        $submitButton = Html::button(Html::i('search')->addClass('mt-icon'))
            ->addClass('button only-icon')
            ->addAttributes([
                'type' => 'submit',
                'title' => trans('arbory::filter.search'),
                'autocomplete' => 'off',
            ]);

        return $this->createForm(
            Html::div(
                Html::div([$searchInput, $submitButton])
                    ->addClass('search-field')
                    ->addAttributes(['data-name' => 'search'])
            )->addClass('text-search')
        );
    }
}
