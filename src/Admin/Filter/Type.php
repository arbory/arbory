<?php

namespace Arbory\Base\Admin\Filter;

use Arbory\Base\Admin\Grid\Filter;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;

class Type extends Filter
{
    /**
     * @return string
     */
    public function __toString() {
        return (string) $this->render();
    }

    public function getModel(){

    }
}