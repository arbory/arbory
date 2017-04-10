<?php

namespace CubeSystems\Leaf\Generator;

use CubeSystems\Leaf\Generator\Extras\Field;
use Illuminate\Support\Collection;

Class GeneratorFormatter
{
    /**
     * @param Schema $schema
     * @return mixed[]
     */
    public function getSchemaTable( Schema $schema )
    {
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
     * @param Collection|string $to
     * @param int $times
     * @return Collection
     */
    public function prependSpacing( $to, $times = 1 )
    {
        if( !$to instanceof Collection )
        {
            $to = new Collection( $to );
        }

        return $to->transform( function( $item, $key ) use ( $times )
        {
            if( $key === 0 )
            {
                return $item;
            }

            return str_repeat( "\t", $times ) . $item;
        } );
    }

    /**
     * @param Collection $fields
     * @return Collection
     */
    public function useFields( Collection $fields )
    {
        return $fields->transform( function( $field )
        {
            /**
             * @var Field $field
             */
            return $this->use( $field->getType() );
        } )->unique();
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
    public function use( $value )
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
}