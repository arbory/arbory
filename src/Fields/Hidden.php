<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Html;

class Hidden extends AbstractField
{
    public function render()
    {
        return Html::input()
                ->setType('hidden')
                ->setValue($this->getValue())
                ->setName($this->getInputName());
    }
}
