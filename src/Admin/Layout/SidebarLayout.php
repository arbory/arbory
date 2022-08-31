<?php

namespace Arbory\Base\Admin\Layout;

use Illuminate\Support\Arr;
use Arbory\Base\Html\Elements\Content;

class SidebarLayout extends AbstractLayout implements LayoutInterface
{
    /**
     * Sidebar width.
     *
     * @var int
     */
    protected $width = 3;

    /**
     * @var Slot
     */
    protected $sidebar;

    public function __construct($contents = null)
    {
        $this->sidebar = $this->slot('sidebar', $contents);
    }

    /**
     * @return void
     */
    public function build()
    {
        $grid = new GridLayout(new Grid());

        $grid->setWidth(Grid::SIZE_MAX - $this->getWidth());
        $grid->addColumn($this->getWidth(), $this->sidebar->render());

        $this->use($grid);
    }

    /**
     * @param  mixed  $content
     * @return Content
     */
    public function contents($content)
    {
        return new Content(Arr::wrap($content));
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getSidebar(): Slot
    {
        return $this->sidebar;
    }

    /**
     * @param  Slot  $sidebar
     */
    public function setSidebar($sidebar): self
    {
        $this->sidebar->setContents($sidebar);

        return $this;
    }
}
