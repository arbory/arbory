<?php

namespace CubeSystems\Leaf\Builders;

use CubeSystems\Leaf\Builders\Table\Column;
use CubeSystems\Leaf\Builders\Table\Row;

class Table
{
    /**
     * @var InterfaceView
     */
    protected $view;

    /**
     * @var Column[]
     */
    protected $columns = [];

    /**
     * @var Row[]
     */
    protected $rows = [];

    /**
     * @param InterfaceView $view
     * @param \Traversable $collection
     * @param array $columns
     */
    public function __construct( InterfaceView $view, \Traversable $collection, array $columns = [] )
    {
        $this->setView( $view );
        $this->setRows( $collection );
        $this->setColumns( $columns );
    }

    /**
     * @param InterfaceView $view
     */
    public function setView( $view )
    {
        $this->view = $view;
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function setColumns( array $columns )
    {
        foreach( $columns as $name )
        {
            $this->addColumn( $name );
        }

        return $this;
    }

    /**
     * @param \Traversable $items
     * @return $this
     */
    public function setRows( \Traversable $items )
    {
        foreach( $items as $item )
        {
            $this->rows[] = new Row( $this, $item );
        }

        return $this;
    }

    /**
     * @param $name
     * @param array $parameters
     * @return Column
     */
    public function addColumn( $name, $parameters = [] )
    {
        $column = new Column( $this, $name, $parameters );

        $this->columns[$name] = $column;

        return $column;
    }

    /**
     * @return Column[]
     */
    public function columns()
    {
        return $this->columns;
    }

    /**
     * @param $name
     * @return Column|null
     */
    public function column( $name )
    {
        if( !isset( $this->columns[$name] ) )
        {
            return null;
        }

        return $this->columns[$name];
    }

    /**
     * @return Row[]
     */
    public function rows()
    {
        return $this->rows;
    }
}
