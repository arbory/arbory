<?php


namespace Arbory\Base\Admin\Layout;

class SidebarLayout extends AbstractLayout implements LayoutInterface
{
    /**
     * Sidebar width
     *
     * @var int
     */
    protected $width = 3;

    /**
     * @var Slot
     */
    protected $sidebar;

    public function __construct(callable $contents =  null)
    {
//        $this->sidebar = new Slot('sidebar', $contents);
    }

    function build()
    {
        $grid = new GridTemplate(new Grid());

        $grid->setWidth(Grid::SIZE_MAX - $this->getWidth());
        $grid->column($this->getWidth(), $this->sidebar);

        $this->use($grid);
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return SidebarLayout
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return Slot
     */
    public function getSidebar(): Slot
    {
        return $this->sidebar;
    }
}