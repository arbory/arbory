<?php


namespace Arbory\Base\Admin\Grid;


use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Layout\AbstractLayout;
use Arbory\Base\Admin\Layout\LayoutInterface;
use Arbory\Base\Admin\Widgets\SearchField;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;

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

    protected function header()
    {
        return Html::header(
            [
//                $this->getBreadcrumbs(),
                $this->searchField(),
            ]
        );
    }


    public function build()
    {
        $this->setContent($this->getGrid()->render());
    }

    function contents($content)
    {
        return new Content([
            $this->header(),
            $content
        ]);
    }
}