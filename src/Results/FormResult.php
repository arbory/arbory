<?php

namespace CubeSystems\Leaf\Results;

use Illuminate\Support\Collection;

/**
 * Class FormResult
 * @package CubeSystems\Leaf\Results
 */
class FormResult extends Collection implements ResultInterface
{
    /**
     * @return array|\CubeSystems\Leaf\Fields\FieldInterface[]
     */
    public function getFields()
    {
        return $this->all();
    }
}
