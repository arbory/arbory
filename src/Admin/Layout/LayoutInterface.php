<?php


namespace Arbory\Base\Admin\Layout;

use Illuminate\Contracts\Support\Renderable;

/**
 * Interface LayoutInterface
 *
 * @package Arbory\Base\Admin\Layout
 */
interface LayoutInterface extends Renderable,TransformableInterface
{
    public function setContent($content):self;
    public function getContent();

    /**
     * @param LayoutInterface|string $layout
     *
     * @return mixed
     */
    public function use($layout);
}