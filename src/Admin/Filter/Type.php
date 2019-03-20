<?php

namespace Arbory\Base\Admin\Filter;

class Type
{
    protected $action;

    /**
     * @return array
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function __toString() {
        return (string) $this->render();
    }
}