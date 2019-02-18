<?php
namespace Arbory\Base\Admin\Panels;

use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Admin\Widgets\Button;

class SimplePanel implements PanelInterface
{
    protected $contents;
    protected $title;
    protected $buttons = [];
    protected $toolbox;

    public function __construct()
    {
        $this->toolbox = new ToolboxMenu(null);
    }

    /**
     * @param ToolboxMenu $toolbox
     *
     * @return ToolboxMenu
     */
    public function toolbox( ToolboxMenu $toolbox ): ToolboxMenu
    {
        return $this->toolbox;
    }

    /**
     * @return Button[]
     */
    public function buttons()
    {
        return $this->buttons;
    }

    /**
     * @return mixed
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function contents()
    {
        return $this->contents;
    }

    /**
     * @param mixed $contents
     *
     * @return SimplePanel
     */
    public function setContents( $contents )
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * @param mixed $title
     *
     * @return SimplePanel
     */
    public function setTitle( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param mixed $buttons
     *
     * @return SimplePanel
     */
    public function setButtons( $buttons ): self
    {
        $this->buttons = $buttons;

        return $this;
    }

    /**
     * @param $name
     * @param $url
     *
     * @return SimplePanel
     */
    public function addToolbox( $name, $url )
    {
        $this->toolbox->add($name, $url);

        return $this;
    }

    public function addButton(Button $button)
    {
        $this->buttons[] = $button;

        return $this;
    }

    public function render()
    {
        return $this->contents();
    }
}