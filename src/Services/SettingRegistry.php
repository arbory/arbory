<?php

namespace Arbory\Base\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Arbory\Base\Admin\Settings\Setting;
use Arbory\Base\Admin\Form\Fields\Translatable;
use Arbory\Base\Admin\Settings\SettingDefinition;

class SettingRegistry
{
    /**
     * @var Collection
     */
    protected $settings;

    /**
     * SettingRegistry constructor.
     */
    public function __construct()
    {
        $this->settings = new Collection();
    }

    /**
     * @return void
     */
    public function register(SettingDefinition $definition)
    {
        $this->settings->put($definition->getKey(), $definition);
    }

    /**
     * @return SettingDefinition|null
     */
    public function find(string $key)
    {
        return $this->settings->get($key);
    }

    /**
     * @return bool
     */
    public function contains(string $key)
    {
        return $this->settings->has($key);
    }

    public function getSettings(): Collection
    {
        return $this->settings;
    }

    /**
     * @return void
     */
    public function importFromDatabase()
    {
        Setting::with('translations', 'file')->get()->each(function (Setting $setting) {
            $definition = $this->find($setting->name);

            if ($definition) {
                $definition->setModel($setting);
                $definition->setValue($setting->value);
            }
        });
    }

    /**
     * @param  string  $before
     */
    public function importFromConfig(array $properties, $before = '')
    {
        foreach ($properties as $key => $data) {
            if (is_array($data) && ! empty($data) && ! array_key_exists('value', $data)) {
                $this->importFromConfig($data, $before.$key.'.');
            } else {
                $key = $before.$key;
                $value = $data['value'] ?? $data;
                $type = $data['type'] ?? null;

                if ($type) {
                    $value = Arr::get($data, 'value');
                }

                if (is_array($value) && $type === Translatable::class) {
                    $value = Arr::get($value, 'value');
                    $value = Arr::get($value, request()->getLocale(), $value);
                }

                $definition = new SettingDefinition($key, $value, $type, $data);
                $this->register($definition);
            }
        }
    }
}
