<?php


namespace Arbory\Base\Admin\Blocks;


use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Admin\Widgets\Button;

class SimpleBlock implements BlockInterface
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
        // TODO: Implement toolbox() method.
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
     * @return SimpleBlock
     */
    public function setContents( $contents )
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * @param mixed $title
     *
     * @return SimpleBlock
     */
    public function setTitle( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param mixed $buttons
     *
     * @return SimpleBlock
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
     * @return SimpleBlock
     */
    public function addToolbox( $name, $url )
    {
        $this->toolbox->add($name, $url);

        return $this;
    }
}