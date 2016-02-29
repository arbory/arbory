<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Exceptions\BadMethodCallException;

/**
 * Class FieldFactory
 * @package CubeSystems\Leaf\Fields
 */
class FieldFactory
{
    /**
     * @var array|null
     */
    protected static $fieldTypes;

    /**
     * @param $type
     * @param null $name
     * @return FieldInterface
     * @throws BadMethodCallException
     */
    public static function getFieldByType( $type, $name = null )
    {
        $class = self::getClassByType( $type );

        return new $class( $name );
    }

    /**
     * @param $type
     * @return string
     * @throws BadMethodCallException
     */
    public static function getClassByType( $type )
    {
        $fieldTypes = static::getFieldTypes();

        if( !array_key_exists( $type, $fieldTypes ) )
        {
            throw new BadMethodCallException( 'Field with type "' . $type . '"" not defined' );
        }

        return $fieldTypes[$type];
    }

    /**
     *
     */
    protected static function loadFieldTypes()
    {
        static::$fieldTypes = config( 'leaf.field_types' );
    }

    /**
     * @return array
     */
    protected static function getFieldTypes()
    {
        if( static::$fieldTypes === null )
        {
            static::loadFieldTypes();
        }

        return static::$fieldTypes;
    }

}
