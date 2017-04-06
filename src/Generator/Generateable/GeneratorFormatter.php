<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Generator\Generateable\Extras\Field;
use Illuminate\Support\Collection;

trait GeneratorFormatter
{
    /**
     * @param Collection $collection
     * @param int $times
     * @return Collection
     */
    public function prependSpacing( Collection $collection, $times = 1 )
    {
        return $collection->transform( function( $item, $key ) use ( $times )
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
        return $fields->transform( function( $field ) {
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
    public function use ( $value )
    {
        return 'use ' . $value . ';';
    }
}