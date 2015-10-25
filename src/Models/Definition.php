<?php

namespace CubeSystems\Leaf\Models;

use CubeSystems\Leaf\Models\Definition\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Descriptor
 * @package CubeSystems\Leaf\Models
 */
class Definition
{
    /**
     * @var Collection
     */
    protected $fields;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct( Model $model )
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function getFields()
    {
        if( $this->fields === null )
        {
            $this->fields = new Collection();

            $columns = \DB::select( "SHOW COLUMNS FROM " . $this->model->getTable() );

            if( $columns )
            {
                foreach( $columns as $column )
                {
                    $this->fields->put( $column->Field, Field::create( $column ) );
                }
            }
        }

        return $this->fields;
    }

    /**
     * @param $field
     * @return Field|null
     */
    public function getField( $field )
    {
        return $this->getFields()->get( $field );
    }

}
