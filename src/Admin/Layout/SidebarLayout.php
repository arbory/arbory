<?php


namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Html\Elements\Content;

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

    public function __construct($contents = null)
    {
        $this->sidebar = $this->slot('sidebar', $contents);
    }

    function build()
    {
        $grid = new GridLayout(new Grid());

        $grid->setWidth(Grid::SIZE_MAX - $this->getWidth());
        $grid->addColumn($this->getWidth(), $this->sidebar->render());

        $this->use($grid);
    }

    public function contents($content)
    {
        return new Content(
            [$content]
        );
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

    /**
     * @param Slot $sidebar
     *
     * @return SidebarLayout
     */
    public function setSidebar($sidebar): SidebarLayout
    {
        $this->sidebar->setContents($sidebar);

        return $this;
    }
}