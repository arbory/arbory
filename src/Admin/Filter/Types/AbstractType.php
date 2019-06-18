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
     * @var array
     */
    protected $value;

    /**
     * @var iterable
     */
    protected $configuration;

    /**
     * @param $value
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
    public function getConfiguration(): iterable
    {
        return $this->configuration;
    }

    /**
     * @param iterable $configuration
     * @return FilterTypeInterface
     */
    public function setConfiguration(iterable $configuration): FilterTypeInterface
    {
        $this->configuration = $configuration;

        return $this;
    }

}