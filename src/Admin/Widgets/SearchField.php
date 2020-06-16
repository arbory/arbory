<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class SearchField.
 */
class SearchField implements Renderable
{
    /**
     * @var
     */
    protected $action;

    /**
     * @var string
     */
    protected $name;

    /**
     * SearchField constructor.
     * @param $action
     */
    public function __construct($action)
    {
        $this->action = $action;
        $this->name = 'search';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
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

    /**
     * @return Element
     */
    public function render()
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
