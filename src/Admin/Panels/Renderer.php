<?php


namespace Arbory\Base\Admin\Panels;


use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Html\Html;

class Renderer
{
    public function render(PanelInterface $block)
    {
        return Html::div(
            [
                $this->header($block),
                Html::div($block->getContents())
                    ->addClass('content'),
            ]
        )->addClass('panel');
    }

    protected function header(PanelInterface $block)
    {
        $toolbox = $block->toolbox($block->getToolbox());

        $header = Html::header(
            [
                Html::div(
                    $block->getTitle()
                )->addClass('title'),
                Html::div(
                    [
                        Html::div($block->getButtons())->addClass('buttons'),
                        $toolbox ? $toolbox->render() : null,
                    ]
                )->addClass('extras'),
            ]
        );

        return $header;
    }
}