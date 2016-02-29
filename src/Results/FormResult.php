<?php

namespace CubeSystems\Leaf\Results;

use CubeSystems\Leaf\Fields\FieldInterface;

/**
 * Class FormResult
 * @package CubeSystems\Leaf\Results
 */
class FormResult implements ResultInterface
{
    /**
     * @var FieldInterface[]|array
     */
    protected $fields = [ ];

    /**
     * @param FieldInterface $field
     */
    public function addField( FieldInterface $field )
    {
        $this->fields[$field->getName()] = $field;
    }

    /**
     * @return array|\CubeSystems\Leaf\Fields\FieldInterface[]
     */
    public function getFields()
    {
        return $this->fields;
    }
}
