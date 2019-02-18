<?php
namespace Arbory\Base\Admin\Panels;


use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Admin\Widgets\Button;
use Illuminate\Contracts\Support\Renderable;

interface PanelInterface extends Renderable
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