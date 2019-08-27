<?php

namespace Arbory\Base\Admin\Filter\Config;

class SelectLikeTypeConfig extends BaseConfig
{
    /**
     * Accepts a key => value iterable array.
     */
    public const CONFIG_OPTIONS = 'options';

    /**
     * Accepts bool.
     */
    public const CONFIG_MULTIPLE = 'multiple';

    /**
     * @param iterable $options
     * @return SelectLikeTypeConfig
     */
    public function setOptions(iterable $options): self
    {
        $this->set(static::CONFIG_OPTIONS, $options);

        return $this;
    }

    /**
     * @return iterable
     */
    public function getOptions(): iterable
    {
        return $this->get(static::CONFIG_OPTIONS);
    }

    /**
     * @param bool $options
     * @return SelectLikeTypeConfig
     */
    public function setMultiple(?bool $options): self
    {
        $this->set(static::CONFIG_OPTIONS, $options);

        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiple(): ?bool
    {
        return $this->get(static::CONFIG_MULTIPLE);
    }
}
