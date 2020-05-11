<?php

namespace Arbory\Base\Admin\Traits;

use Illuminate\Contracts\Support\Renderable as RenderableInterface;

trait Renderable
{
    /**
     * @var RenderableInterface
     */
    protected $renderer;

    /**
     * @param RenderableInterface $renderer
     * @return self
     */
    public function setRenderer(RenderableInterface $renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @return RenderableInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->renderer->render();
    }
}
