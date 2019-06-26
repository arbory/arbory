<?php


namespace Arbory\Base\Admin\Filter\Types;


use Arbory\Base\Admin\Filter\FilterTypeInterface;

/**
 * Class AbstractType
 * @package Arbory\Base\Admin\Filter\Types
 */
abstract class AbstractType
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var iterable
     */
    protected $config;

    /**
     * @param mixed $value
     * @return mixed
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return iterable
     */
    public function getConfig(): iterable
    {
        return $this->config;
    }

    /**
     * @param iterable $config
     * @return FilterTypeInterface
     */
    public function setConfig(iterable $config): FilterTypeInterface
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        $value = $this->getValue();

        if(is_array($value)) {
            $value = array_filter($value, 'blank');
        }

        return blank($value);
    }
}