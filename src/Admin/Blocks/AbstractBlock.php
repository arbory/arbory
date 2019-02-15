<?php


namespace Arbory\Base\Admin\Blocks;


use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Admin\Widgets\Button;

class AbstractBlock implements BlockInterface
{

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
        // TODO: Implement buttons() method.
    }

    /**
     * @return mixed
     */
    public function title()
    {
        // TODO: Implement title() method.
    }

    /**
     * @return mixed
     */
    public function contents()
    {
        // TODO: Implement contents() method.
    }
}