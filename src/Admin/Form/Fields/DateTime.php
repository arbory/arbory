<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Elements\Element;

/**
 * Class DateTime
 * @package Arbory\Base\Admin\Form\Fields
 */
class DateTime extends Text
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
    }

    /**
     * @return Element
     */
    public function render()
    {
        return (new Renderer\DateFieldRenderer($this))->render();
    }
}
