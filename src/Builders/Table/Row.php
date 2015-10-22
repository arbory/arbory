<?php

namespace CubeSystems\Leaf\Builders\Table;

use CubeSystems\Leaf\Builders\Table;

class Row
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var array
     */
    protected $item;

    /**
     * @param Table $table
     * @param $item
     */
    public function __construct( Table $table, $item )
    {
        $this->table = $table;
        $this->item = $item;
    }

    public function id()
    {
        return $this->item['id'];
    }

    /**
     * @return Cell[]
     */
    public function cells()
    {
        $cells = [ ];

        foreach( $this->table->columns() as $column )
        {
            $value = $this->item[$column->name()];

            $cells[] = new Cell( $column, $this, $value );
        }

        return $cells;
    }
}
