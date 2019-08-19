<?php

namespace Arbory\Base\Admin\Panels;

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
     * @param  PanelInterface  $panel
     */
    public function __construct(PanelInterface $panel)
    {
        $this->panel = $panel;
    }

    public function render()
    {
        $wrapper = $this->panel->getWrapper();

        $element = Html::div(
            [
                $this->header(),
                Html::div($this->panel->getContent())
                    ->addClass('content'),
            ]
        )->addClass('panel')
                       ->addClass(implode(' ', $this->panel->getClasses()))
                       ->addAttributes($this->panel->getAttributes());

        return $wrapper ? $wrapper($element) : $element;
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
