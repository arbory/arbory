<?php

namespace CubeSystems\Leaf\Builders\Table;

use CubeSystems\Leaf\Builders\Table;

class Column
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Callable|\Closure
     */
    protected $formatter;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @param Table $table
     * @param $name
     * @param array $parameters
     */
    public function __construct( Table $table, $name, array $parameters = [] )
    {
        $this->table = $table;
        $this->name = $name;
        $this->parameters = $parameters;

        if( isset( $this->parameters['formatter'] ) && is_callable( $this->parameters['formatter'] ) )
        {
            $this->setFormatter( $this->parameters['formatter'] );
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name();
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param Callable|\Closure $callable
     */
    public function setFormatter( $callable )
    {
        $this->formatter = $callable;
    }

    /**
     * @return bool
     */
    public function hasFormatter()
    {
        return !empty( $this->formatter );
    }

    /**
     * @return Callable|\Closure
     */
    public function formatter()
    {
        if( $this->hasFormatter() )
        {
            return $this->formatter;
        }
        else
        {
            return function( Cell $cell ) {
                return "<span>" . e( (string) $cell->value() ) . "</span>";
            };
        }
    }
}
