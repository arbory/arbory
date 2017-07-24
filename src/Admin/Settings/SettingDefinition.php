<?php

namespace CubeSystems\Leaf\Admin\Settings;

use CubeSystems\Leaf\Admin\Form\Fields\Text;

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
     * @param string $key
     * @param mixed $value
     * @param string|null $type
     * @param mixed $configEntry
     */
    public function __construct( string $key, $value = null, string $type = null, $configEntry = null )
    {
        $this->key = $key;
        $this->value = $value;
        $this->type = $type ?? Text::class;
        $this->configEntry = $configEntry;
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
    public function setKey( string $key )
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
    public function setType( string $type )
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
    public function setValue( $value )
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
     * @return void
     */
    public function save()
    {
        $setting = new Setting( $this->toArray() );

        if( $this->isInDatabase() )
        {
            $setting->exists = true;
            $setting->update();
        }
        else
        {
            $setting->save();
        }
    }

    /**
     * @return bool
     */
    public function isInDatabase(): bool
    {
        return Setting::query()->where( 'name', $this->getKey() )->exists();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->key,
            'value' => $this->value,
            'type' => $this->type
        ];
    }
}