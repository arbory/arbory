<?php


namespace Arbory\Base\Admin\Grid;


use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Layout\AbstractLayout;
use Arbory\Base\Admin\Layout\Body;
use Arbory\Base\Admin\Layout\LayoutInterface;
use Arbory\Base\Admin\Widgets\SearchField;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Closure;

class Layout extends AbstractLayout implements LayoutInterface
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @return Grid
     */
    public function getGrid(): Grid
    {
        return $this->grid;
    }

    /**
     * @param Grid $grid
     *
     * @return Layout
     */
    public function setGrid(Grid $grid): Layout
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    protected function searchField()
    {
        if (!$this->grid->hasTool('search')) {
            return null;
        }

        return (new SearchField($this->grid->getModule()->url('index')))->render();
    }

    public function build()
    {
        $this->setContent($this->getGrid()->render());
    }

    public function apply(Body $body, Closure $next, array ...$parameters)
    {
        $body->getRoot()->slot('header_right', $this->searchField());

        return parent::apply($body, $next, $parameters);
    }

    public function contents($content)
    {
        return new Content([
            $content
        ]);
    }
}