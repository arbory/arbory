<?php

namespace Arbory\Base\Services;

use Arbory\Base\Admin\Settings\Setting;

class SettingFactory
{
    /**
     * @var SettingRegistry
     */
    protected $settingRegistry;

    /**
     * @param SettingRegistry $settingRegistry
     */
    public function __construct(SettingRegistry $settingRegistry)
    {
        $this->settingRegistry = $settingRegistry;
    }

    /**
     * @param string $key
     * @return Setting
     */
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
