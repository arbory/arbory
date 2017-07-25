<?php

namespace Arbory\Base\Generator\Extras;

class Relation
{
    /**
     * @var string
     */
    protected $fieldType;

    /**
     * @var string
     */
    protected $model;

    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    /**
     * @param string $fieldType
     */
    public function setFieldType( string $fieldType )
    {
        $this->fieldType = $fieldType;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel( string $model )
    {
        $this->model = $model;
    }
}
