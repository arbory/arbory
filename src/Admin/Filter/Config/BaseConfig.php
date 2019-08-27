<?php

namespace Arbory\Base\Admin\Filter\Config;

use ReflectionClass;
use Illuminate\Support\Str;
use Arbory\Base\Support\ExtendedFluent;

class BaseConfig extends ExtendedFluent
{
    private const PREFIX_CONFIG = 'CONFIG_';

    /**
     * Returns defined config options.
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function getAvailable(): array
    {
        $reflection = new ReflectionClass(static::class);

        $available = [];

        foreach ($reflection->getConstants() as $constant) {
            if (Str::startsWith($constant, self::PREFIX_CONFIG)) {
                $available[] = Str::after($constant, self::PREFIX_CONFIG);
            }
        }

        return $available;
    }
}
