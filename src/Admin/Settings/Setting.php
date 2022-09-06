<?php

namespace Arbory\Base\Admin\Settings;

use Arbory\Base\Files\ArboryFile;
use Arbory\Base\Services\SettingRegistry;
use Arbory\Base\Support\Translate\Translatable;
use ErrorException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

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
    protected string $translationForeignKey = 'setting_name';

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
    protected array $translatedAttributes = [
        'value',
    ];

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }

    /**
     * @return Model|self
     *
     * @throws MassAssignmentException
     * @throws ErrorException
     */
    public function fill(array $attributes): Model|self
    {
        $name = Arr::get($attributes, 'name');

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
    public function setAttribute($key, $value): Model|self
    {
        if ($this->isTranslatable()) {
            return $this->setTranslatableAttribute($key, $value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
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
     * @return BelongsTo
     */
    public function value(mixed $column = null)
    {
        return $column ? parent::value($column) : $this->file();
    }

    /**
     * @return BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(ArboryFile::class, 'value');
    }

    /**
     * @param string|null $settingName
     */
    public function isTranslatable(string $settingName = null): bool
    {
        $settingName ??= $this->name;

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
