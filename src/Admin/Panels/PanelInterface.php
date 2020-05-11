<?php

namespace Arbory\Base\Admin\Panels;

use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Admin\Widgets\Button;
use Illuminate\Contracts\Support\Renderable;
use Arbory\Base\Admin\Form\Fields\RenderOptionsInterface;

interface PanelInterface extends Renderable, RenderOptionsInterface
{
    /**
     * @param  Toolbox  $toolbox
     *
     * @return Toolbox
     */
    public function toolbox(Toolbox $toolbox): Toolbox;

    /**
     * @return Button[]
     */
    public function getButtons();

    /**
     * @return mixed
     */
    public function getTitle();

    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @return mixed
     */
    public function getToolbox(): ?Toolbox;

    public function build();
}
