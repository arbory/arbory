<?php

namespace Arbory\Base\Admin\Filter;

class Type
{
    /**
     * @return string
     */
    public function __toString() {
        return (string) $this->render();
    }
}