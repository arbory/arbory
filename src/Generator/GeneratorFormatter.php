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
     * @param Collection|string $items
     * @param int $times
     * @return Collection
     */
    public function indent( $items, $times = 1 )
    {
        if( !$items instanceof Collection )
        {
            $items = new Collection( $items );
        }

        return $items->transform( function( $item ) use ( $times )
        {
            $lines = explode( PHP_EOL, $item );

            next($lines);

            foreach( $lines as  &$line )
            {
                $line = str_repeat( "\t", $times ) . $line;
            }

            return count( $lines ) === 1 ? $item : implode( PHP_EOL, $lines );
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
     * @param Collection $relations
     * @return Collection
     */
    public function useRelations( Collection $relations )
    {
        return $relations->transform( function( $relation )
        {
            /**
             * @var Relation $relation
             */
            return $this->use( $relation->getModel() );
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