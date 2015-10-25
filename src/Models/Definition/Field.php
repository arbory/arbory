<?php

namespace CubeSystems\Leaf\Models\Definition;

/**
 * Class Field
 * @package CubeSystems\Leaf\Models\Descriptor
 */
class Field
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $size;

    /**
     * @var string
     */
    protected $null;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $default;

    /**
     * @var string
     */
    protected $extra;

    /**
     * @param \stdClass $column
     * @return static
     */
    public static function create( \stdClass $column )
    {
        $field = new static;
        $field->name = $column->Field;
        $field->key = $column->Key;
        $field->default = $column->Default;
        $field->extra = $column->Extra;

        if( preg_match( '/^(?P<type>\w+)[\(]?(?P<size>[\d,]*)[\)]?( |$)/', $column->Type, $matches ) )
        {
            $field->type = $matches['type'];

            if( $matches['size'] )
            {
                if( ( $position = strpos( $matches['size'], ',' ) ) !== false )
                {
                    $field->type = (int) substr( $matches['size'], 0, $position );
                }
                else
                {
                    $field->type = (int) $matches['size'];
                }
            }
        }
        elseif( preg_match( '/^(?P<type>\w+)\(/', $column->Type, $matches ) )
        {
            $field->type = $matches['type'];
        }
        else
        {
            $field->type = $column->Type;
        }

        return $field;
    }

}
