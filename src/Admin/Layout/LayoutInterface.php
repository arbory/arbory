<?php


namespace Arbory\Base\Admin\Layout;

use Closure;
use Illuminate\Contracts\Support\Renderable;

/**
 * Interface LayoutInterface
 *
 * @package Arbory\Base\Admin\Layout
 */
interface LayoutInterface extends Renderable, TransformableInterface
{
    public function setContent($content):self;
    public function getContent();

    public function setWrapper($wrapper);

    public function contents($content);

    /**
     * @param LayoutInterface|string $layout
     *
     * @return mixed
     */
    public function use($layout);
}