<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Elements\Element;

/**
 * Class DateTime
 * @package Arbory\Base\Admin\Form\Fields
 */
class DateTime extends Text
{
    protected $classes = [
        'text',
        'datetime-picker'
    ];

    public function getValue()
    {
        $value = parent::getValue();
        
        return $value ? date('Y-m-d H:i', strtotime($value)) : null;
    }
}
