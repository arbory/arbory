<?php

namespace Arbory\Base\Admin\Layout;

/**
 * A Block of content which is wrapped.
 *
 * Interface WrappableInterface
 */
interface WrappableInterface
{
    /**
     * Set the inner content.
     *
     * @param $content
     *
     * @return mixed
     */
    public function setContent($content);

    /**
     * Returns inner content.
     *
     * @return mixed
     */
    public function getContent();

    /**
     * Renders wrapped content.
     *
     * @return mixed
     */
    public function render();
}
