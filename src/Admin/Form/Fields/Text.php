<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Controls\InputControl;

/**
 * Class Text.
 */
class Text extends ControlField
{
    protected $control = InputControl::class;

    protected $classes = [
        'text',
    ];
}
