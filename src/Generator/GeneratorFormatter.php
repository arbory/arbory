<?php

namespace CubeSystems\Leaf\Generator;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Extras\Relation;
use Illuminate\Support\Collection;

Class GeneratorFormatter
{
    /**
     * @param Schema $schema
     * @return mixed[]|null
     */
    public function getSchemaTable( Schema $schema )
    {
        if( $schema->getFields()->isEmpty() )
        {
            return null;
        }

        $header = array_merge(
            [ 'name' ],
            array_keys( $schema->getFields()->first()->getStructure()->values() )
        );

        $body = ( clone $schema->getFields() )->transform( function( $item )
        {
            /** @var Field $item */
            return array_merge( [ $item->getName() ], $item->getStructure()->values() );
        } );

        return [ $header, $body ];
    }

    /**
     * @param string $name
     * @return string
     */
    public function property( $name )
    {
        $name = str_replace( '.', '_', $name );

        return camel_case( $name );
    }

    /**
     * @param string $name
     * @return string
     */
    public function field( $name )
    {
        return snake_case( $name );
    }

    /**
     * @param string
     * @param int $times
     * @return Collection
     */
    public function indent( $item, $times = 1 )
    {
        $lines = explode( PHP_EOL, $item );

        foreach( $lines as $key => &$line )
        {
            if( $key === 0 )
            {
                continue;
            }

            $line = str_repeat( "\t", $times ) . $line;
        }

        return count( $lines ) === 1 ? $item : implode( PHP_EOL, $lines );
    }

    /**
     * @param string $value
     * @return string
     */
    public function className( string $value )
    {
        return ucfirst( camel_case( $value ) );
    }

    /**
     * @param string $value
     * @return string
     */
    public function use ( $value )
    {
        return 'use ' . $value . ';';
    }

    /**
     * @param string $var
     * @return Collection
     */
    public function docBlock( $var )
    {
        $doc = new Collection();

        $doc->push( '/**' );
        $doc->push( ' * @var ' . $var );
        $doc->push( ' */' );

        return $doc;
    }

    /**
     * @param string $message
     * @param mixed $default
     * @param mixed $hint
     * @return string
     */
    public function line( string $message, $default = null, $hint = null ): string
    {
        return sprintf(
            ' <fg=blue>%s</>%s%s:' . PHP_EOL . ' > ',
            $message,
            $this->lineHint( $hint ),
            $this->lineDefault( $default )
        );
    }

    /**
     * @param mixed $default
     * @return string
     */
    public function lineDefault( $default = null ): string
    {
        if( !$default )
        {
            return (string) null;
        }

        return sprintf(
            ' [<fg=yellow>%s</>]',
            is_bool( $default ) ? $this->boolToString( $default ) : $default
        );
    }

    /**
     * @param mixed $hint
     * @return string
     */
    public function lineHint( $hint = null ): string
    {
        if( !$hint )
        {
            return (string) null;
        }

        return sprintf(
            ' (<fg=yellow>%s</>)',
            $hint
        );
    }

    /**
     * @param bool $value
     * @return string
     */
    public function boolToString( bool $value )
    {
        return $value ? 'yes' : 'no';
    }
}