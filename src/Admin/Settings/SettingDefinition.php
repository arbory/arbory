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
    protected $key;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var mixed
     */
    protected $configEntry;

    /**
     * @var Setting|null
     */
    protected $model;

    /**
     * @param string $key
     * @param mixed $value
     * @param string|null $type
     * @param mixed $configEntry
     * @param Setting|null $databaseEntry
     */
    public function __construct(
        string $key,
        $value = null,
        string $type = null,
        $configEntry = null,
        Setting $databaseEntry = null
    ) {
        $this->key = $key;
        $this->value = $value;
        $this->type = $type ?? Text::class;
        $this->configEntry = $configEntry;
        $this->model = $databaseEntry;
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

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
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

    /**
     * @param mixed $value
     */
    public function setValue($value)
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
     * @param Setting|null $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return bool
     */
    public function isInDatabase(): bool
    {
        return Setting::query()->where('name', $this->getKey())->exists();
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return in_array($this->getType(), [
            ArboryFile::class,
            ArboryImage::class,
        ], true);
    }

    /**
     * @return bool
     */
    public function isImage(): bool
    {
        return $this->getType() === ArboryImage::class;
    }

    /**
     * @return bool
     */
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
