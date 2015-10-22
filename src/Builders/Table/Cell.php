<?php

namespace CubeSystems\Leaf\Builders\Table;

class Cell
{
    /**
     * @var Column
     */
    protected $column;

    /**
     * @var Row
     */
    protected $row;

    /**
     * @var string
     */
    protected $value;

    /**
     * @param Column $column
     * @param Row $row
     * @param $value
     */
    public function __construct( Column $column, Row $row, $value )
    {
        $this->column = $column;
        $this->row = $row;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->formattedValue();
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    protected function formattedValue()
    {
        $formatter = $this->column->formatter();

        return $formatter( $this );
    }

}
