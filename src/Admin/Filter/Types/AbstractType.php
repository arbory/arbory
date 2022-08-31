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
     * @return mixed
     */
    public function setValue(mixed $value)
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

    abstract public function render(FilterItem $filterItem): Element;

    public function getConfig(): iterable
    {
        return $this->config;
    }

    public function setConfig(iterable $config): FilterTypeInterface
    {
        $configType = $this->getConfigType();

        if (! $config instanceof BaseConfig && $configType) {
            $config = new $configType($config);
        }

        $this->config = $config;

        return $this;
    }

    public function isEmpty(): bool
    {
        $value = $this->getValue();

        if (is_array($value)) {
            $value = array_filter($value, 'blank');
        }

        return blank($value);
    }

    public function getConfigType(): ?string
    {
        return null;
    }
}
