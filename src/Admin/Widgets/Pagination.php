<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Html;
use Illuminate\Pagination\Paginator;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class Pagination.
 */
class Pagination implements Renderable
{
    /**
     * @var Paginator|LengthAwarePaginator
     */
    private $paginator;

    /**
     * Pagination constructor.
     * @param LengthAwarePaginator $paginator
     */
    public function __construct(LengthAwarePaginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string|Element
     */
    public function render()
    {
        return Html::div([
            $this->getPreviousPageButton(),
            $this->getPagesSelect(),
            $this->getNextPageButton(),
        ])->addClass('pagination');
    }

    /**
     * @return Element
     */
    protected function getPreviousPageButton()
    {
        $previousPage = ($this->paginator->currentPage() > 1)
            ? Html::link()->addAttributes(['href' => $this->paginator->url($this->paginator->currentPage() - 1)])
            : Html::button()->addAttributes(['type' => 'button', 'disabled' => 'disabled']);

        $previousPage->append(Html::i('arrow_left')->addClass('mt-icon'));
        $previousPage->addClass('button only-icon secondary previous');
        $previousPage->addAttributes(['title' => trans('arbory::pagination.previous_page')]);

        return $previousPage;
    }

    /**
     * @return Element
     */
    protected function getPagesSelect()
    {
        $select = Html::select()->setName('page');

        for ($i = 1; $i <= $this->paginator->lastPage(); $i++) {
            $pageStart = ($i - 1) * $this->paginator->perPage() + 1;
            $pageEnd = ($this->paginator->lastPage() === $i)
                ? $this->paginator->total()
                : $i * $this->paginator->perPage();

            $option = Html::option($pageStart.' - '.$pageEnd)->setValue($i);

            if ($this->paginator->currentPage() === $i) {
                $option->select();
            }

            $select->append($option);
        }

        return $select;
    }

    /**
     * @return Element
     */
    protected function getNextPageButton()
    {
        $nextPage = $this->paginator->hasMorePages()
            ? Html::link()->addAttributes(['href' => $this->paginator->url($this->paginator->currentPage() + 1)])
            : Html::button()->addAttributes(['type' => 'button', 'disabled' => 'disabled']);

        $nextPage->append(Html::i('arrow_right')->addClass('mt-icon'));
        $nextPage->addClass('button only-icon secondary next');
        $nextPage->addAttributes(['title' => trans('arbory::pagination.next_page')]);

        return $nextPage;
    }
}
