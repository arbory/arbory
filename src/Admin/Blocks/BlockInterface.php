<?php


namespace Arbory\Base\Admin\Blocks;


use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Admin\Widgets\Button;

interface BlockInterface
{
    /**
     * @param ToolboxMenu $toolbox
     *
     * @return ToolboxMenu
     */
    public function toolbox( ToolboxMenu $toolbox ): ToolboxMenu;

    /**
     * @return Button[]
     */
    public function buttons();

    /**
     * @return mixed
     */
    public function title();

    /**
     * @return mixed
     */
    public function contents();
}