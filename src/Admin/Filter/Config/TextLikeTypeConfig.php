<?php

namespace Arbory\Base\Admin\Filter\Config;

class TextLikeTypeConfig extends BaseConfig
{
    public const CONFIG_TYPE = 'type';

    /**
     * @param string $type
     * @return TextLikeTypeConfig
     */
    public function setType(string $type): self
    {
        $this->set(static::CONFIG_TYPE, $type);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->get(static::CONFIG_TYPE);
    }
}
