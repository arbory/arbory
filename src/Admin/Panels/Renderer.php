<?php

namespace Arbory\Base\Admin\Panels;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

class Renderer implements Renderable
{
    /**
     * Renderer constructor.
     */
    public function __construct(protected PanelInterface $panel)
    {
    }

    public function render(): Element
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

        return Html::header(
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
    }
}
