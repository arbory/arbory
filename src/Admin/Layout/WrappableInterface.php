<?php


namespace Arbory\Base\Admin\Layout;

/**
 * A Block of content which is wrapped
 *
 * Interface WrappableInterface
 * @package Arbory\Base\Admin\Layout
 */
interface WrappableInterface
{
    public function setContent($content);

    public function getContent();

    public function render();
}
