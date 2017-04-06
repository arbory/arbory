<?php

namespace CubeSystems\Leaf\Generators;

class Structure
{
    /**
     * @var string
     */
    protected $type = 'integer';

    /**
     * @var bool
     */
    protected $primary = false;

    /**
     * @var bool
     */
    protected $nullable = false;

    /**
     * @var bool
     */
    protected $autoIncrement = false;

    /**
     * @var mixed
     */
    protected $defaultValue;

    /**
     * @var int
     */
    protected $length = 0;

    /**
     * @return mixed[]
     */
    public function values()
    {
        return [
            'type' => $this->getType(),
            'primary' => $this->isPrimary(),
            'auto_increment' => $this->isAutoIncrement(),
            'nullable' => $this->isNullable(),
            'length' => $this->getLength(),
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType( string $type )
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return bool
     */
    public function isAutoIncrement(): bool
    {
        return $this->autoIncrement;
    }

    /**
     * @param bool $autoIncrement
     */
    public function setAutoIncrement( bool $autoIncrement )
    {
        $this->autoIncrement = $autoIncrement;
    }

    /**
     * @param bool $nullable
     */
    public function setNullable( bool $nullable )
    {
        $this->nullable = $nullable;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     */
    public function setDefaultValue( $defaultValue )
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return bool
     */
    public function isPrimary(): bool
    {
        return $this->primary;
    }

    /**
     * @param bool $primary
     */
    public function setPrimary( bool $primary )
    {
        $this->primary = $primary;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     */
    public function setLength( int $length )
    {
        $this->length = $length;
    }
}