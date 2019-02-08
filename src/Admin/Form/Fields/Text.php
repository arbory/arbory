<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Controls\Input as InputControl;
use Arbory\Base\Html\Elements\Element;

/**
 * Class Text
 * @package Arbory\Base\Admin\Form\Fields
 */
class Text extends ControlField
{
    protected $control = InputControl::class;

    protected $classes = [
        'text'
    ];
}
