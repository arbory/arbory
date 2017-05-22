<?php

namespace CubeSystems\Leaf\Services;

use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Settings\Setting;

class SettingFactory
{
    /**
     * @param string $name
     * @param array $parameters
     * @return Setting
     */
    public static function build( string $name, array $parameters ): Setting
    {
        return new Setting( [
            'name' => $name,
            'value' => $parameters[ 'value' ] ?? null,
            'type' => $parameters[ 'type' ] ?? Text::class
        ] );
    }
}