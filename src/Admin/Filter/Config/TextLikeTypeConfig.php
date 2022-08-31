<?php

namespace Arbory\Base\Admin\Filter\Config;

class TextLikeTypeConfig extends BaseConfig
{
    public const CONFIG_TYPE = 'type';

    public function setType(string $type): self
    {
        $this->set(static::CONFIG_TYPE, $type);

        return $this;
    }

    public function getType(): ?string
    {
        return $this->get(static::CONFIG_TYPE);
    }
}
