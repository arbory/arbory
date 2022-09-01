<?php

namespace Arbory\Base\Admin\Settings;

use Arbory\Base\Admin\Form\Fields\Text;
use Arbory\Base\Admin\Form\Fields\ArboryFile;
use Arbory\Base\Admin\Form\Fields\ArboryImage;
use Arbory\Base\Admin\Form\Fields\Translatable;

class SettingDefinition
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @param  string|null  $type
     */
    public function __construct(
        protected string $key,
        protected mixed $value = null,
        string $type = null,
        protected mixed $configEntry = null,
        protected ?Setting $model = null
    ) {
        $this->type = $type ?? Text::class;
    }

    /**
     * @return void
     */
    public function save()
    {
        $setting = new Setting($this->toArray());

        if ($this->isInDatabase()) {
            $setting->exists = true;
            $setting->update();
        } else {
            $setting->save();
        }
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key)
    {
        $this->key = $key;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setValue(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getConfigEntry()
    {
        return $this->configEntry;
    }

    /**
     * @return Setting|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param  Setting|null  $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    public function isInDatabase(): bool
    {
        return Setting::query()->where('name', $this->getKey())->exists();
    }

    public function isFile(): bool
    {
        return in_array($this->getType(), [
            ArboryFile::class,
            ArboryImage::class,
        ], true);
    }

    public function isImage(): bool
    {
        return $this->getType() === ArboryImage::class;
    }

    public function isTranslatable(): bool
    {
        return $this->getType() === Translatable::class;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->key,
            'value' => $this->value,
            'type' => $this->type,
        ];
    }
}
