<?php

namespace Arbory\Base\Admin\Settings;

use Arbory\Base\Files\ArboryFile;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Services\SettingRegistry;
use Arbory\Base\Support\Translate\Translatable;

class Setting extends Model
{
    use Translatable {
        save as protected translatableSave;
        getAttribute as protected getTranslatableAttribute;
        setAttribute as protected setTranslatableAttribute;
        fill as protected translatableFill;
    }

    /**
     * @var string
     */
    protected $primaryKey = 'name';

    /**
     * @var string
     */
    protected $translationForeignKey = 'setting_name';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'value', 'type',
    ];

    /**
     * @var array
     */
    protected $translatedAttributes = [
        'value',
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param array $attributes
     * @return Model|self
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \ErrorException
     */
    public function fill(array $attributes)
    {
        $name = array_get($attributes, 'name');

        return $this->isTranslatable($name) ? $this->translatableFill($attributes) : parent::fill($attributes);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (in_array($key, $this->translatedAttributes)) {
            if ($this->isTranslatable()) {
                return $this->getTranslatableAttribute($key);
            }

            return parent::getAttributeValue($key);
        }

        return parent::getAttribute($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Model|self
     */
    public function setAttribute($key, $value)
    {
        if ($this->isTranslatable()) {
            return $this->setTranslatableAttribute($key, $value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if ($this->isTranslatable()) {
            return $this->translatableSave($options);
        }

        return parent::save($options);
    }

    /**
     * @param mixed $column
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function value($column = null)
    {
        return $column ? parent::value($column) : $this->file();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(ArboryFile::class, 'value');
    }

    /**
     * @param string|null $settingName
     * @return bool
     */
    public function isTranslatable(string $settingName = null): bool
    {
        $settingName = $settingName ?? $this->name;

        if (! $settingName) {
            return false;
        }

        /**
         * @var SettingRegistry
         * @var SettingDefinition $definition
         */
        $registry = app(SettingRegistry::class);
        $definition = $registry->find($settingName);

        return $definition && $definition->getType() === \Arbory\Base\Admin\Form\Fields\Translatable::class;
    }

    /**
     * @return SettingDefinition|null
     */
    public function getDefinition()
    {
        /**
         * @var SettingRegistry
         * @var SettingDefinition $definition
         */
        $registry = app(SettingRegistry::class);

        return $registry->find($this->name) ?? new SettingDefinition($this->name);
    }
}
