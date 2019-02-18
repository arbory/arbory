<?php


namespace Arbory\Base\Admin\Panels;


use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Html\Html;

class PanelRenderer
{
    public function render(PanelInterface $block)
    {
        return Html::div(
            [
                $this->header($block),
                Html::div($block->contents())->addClass('content')
            ]
        )->addClass('panel');
    }

    protected function header(PanelInterface $block)
    {
        $menu    = $block->toolbox(new ToolboxMenu(null));
        $toolbox = new Toolbox(null, $menu);

        $header = Html::header(
            [
                Html::div(
                    $block->title()
                )->addClass('title'),
                Html::div(
                    [
                        Html::div($block->buttons())->addClass('buttons'),
                        $toolbox->render()
                    ]
                )->addClass('extras')
            ]
        );

        return $header;
    }
}