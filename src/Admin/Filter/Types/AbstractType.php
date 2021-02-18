<?php

namespace Arbory\Base\Admin\Filter\Types;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Filter\FilterItem;
use Arbory\Base\Admin\Filter\Config\BaseConfig;
use Arbory\Base\Admin\Filter\FilterTypeInterface;

/**
 * Class AbstractType.
 */
abstract class AbstractType
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var iterable|BaseConfig
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
     * @param FilterItem $filterItem
     * @return Element
     */
    abstract public function render(FilterItem $filterItem): Element;

    /**
     * @return iterable|BaseConfig
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
        $configType = $this->getConfigType();

        if (! $config instanceof BaseConfig && $configType) {
            $config = new $configType($config);
        }

        $this->config = $config;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        $value = $this->getValue();

        if (is_array($value)) {
            $value = array_filter($value, 'blank');
        }

        return blank($value);
    }

    /**
     * @return string|null
     */
    public function getConfigType(): ?string
    {
        return null;
    }
}
