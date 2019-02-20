<?php


namespace Arbory\Base\Admin\Panels;


use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

class Renderer implements Renderable
{
    /**
     * @var PanelInterface
     */
    protected $panel;

    /**
     * Renderer constructor.
     *
     * @param PanelInterface $panel
     */
    public function __construct(PanelInterface $panel)
    {
        $this->panel = $panel;
    }

    public function render()
    {
        return Html::div(
            [
                $this->header(),
                Html::div($this->panel->getContent())
                    ->addClass('content'),
            ]
        )->addClass('panel');
    }

    protected function header()
    {
        $toolbox = $this->panel->toolbox($this->panel->getToolbox());

        $header = Html::header(
            [
                Html::div(
                    $this->panel->getTitle()
                )->addClass('title'),
                Html::div(
                    [
                        Html::div($this->panel->getButtons())->addClass('buttons'),
                        $toolbox ? $toolbox->render() : null,
                    ]
                )->addClass('extras toolbox-wrap'),
            ]
        );

        return $header;
    }
}