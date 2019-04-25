<?php

namespace Arbory\Base\Admin\Filter\Type;

/**
 * Class BooleanSelect
 * @package Arbory\Base\Admin\Filter\Type
 */
class BooleanSelect extends Select
{
    /**
     * BooleanSelect constructor.
     * @param array|null $content
     * @param string|null $column
     * @param bool $filterNull
     */
    public function __construct($content = null, ?string $column = null, $filterNull = true)
    {
        parent::__construct($content, $column);
        $this->filterNull = $filterNull;
        $this->selected = $this->getSelectedValue();
    }
}
