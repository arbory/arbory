<?php

namespace Arbory\Base\Admin\Settings;

use Illuminate\Support\Collection;
use Arbory\Base\Services\SettingRegistry;

class Settings
{
    public function __construct(protected SettingRegistry $settingRegistry)
    {
        $this->settingRegistry->importFromDatabase();
    }

    /**
     * @return mixed
     */
    public function get(string $key, mixed $default = null)
    {
        $definition = $this->settingRegistry->find($key);

        if (! $definition) {
            return $default;
        }

        $model = $definition->getModel();

        if ($definition->isFile()) {
            return $model->file ?? $default;
        }

        if ($definition->isTranslatable()) {
            if ($model && $model->getAttribute('value')) {
                return $model->getAttribute('value');
            } else {
                return $default;
            }
        }

        return $definition->getValue() ?: $default;
    }

    /**
     * @return bool
     */
    public function has(string $key)
    {
        return (bool) $this->get($key);
    }

    /**
     * @param  mixed  $type
     * @return void
     */
    public function set(string $key, mixed $value, string $type = null)
    {
        $definition = new SettingDefinition($key, $value, $type);

        $this->settingRegistry->register($definition);

        $definition->save();
    }

    public function all(): Collection
    {
        return $this->settingRegistry->getSettings()->mapWithKeys(fn(SettingDefinition $definition) => [$definition->getKey() => $definition->getValue()]);
    }
}
