<?php

namespace Arbory\Base\Admin\Layout;

use Illuminate\Contracts\Support\Renderable;

/**
 * Interface LayoutInterface.
 */
interface LayoutInterface extends Renderable, TransformableInterface
{
    /**
     * @param $content
     * @return LayoutInterface
     */
    public function setContent($content): self;

    /**
     * @return mixed
     */
    public function getContent(): mixed;

    /**
     * @param  mixed  $content
     * @return mixed
     */
    public function contents(mixed $content): mixed;

    /**
     * @return mixed
     */
    public function use(TransformableInterface|LayoutInterface|string $layout): mixed;
}
