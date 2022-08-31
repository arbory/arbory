<?php

namespace Arbory\Base\Services;

use Arbory\Base\Admin\Settings\Setting;

class SettingFactory
{
    public function __construct(protected SettingRegistry $settingRegistry)
    {
    }

    public function build(string $key): Setting
    {
        $definition = $this->settingRegistry->find($key);

        if (! $definition) {
            return null;
        }

        return new Setting([
            'name' => $key,
            'value' => $definition->getValue(),
            'type' => $definition->getType(),
        ]);
    }
}
