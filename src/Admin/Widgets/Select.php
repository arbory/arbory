<?php

namespace CubeSystems\Leaf\Admin\Widgets;

use CubeSystems\Leaf\Html\Html;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

/**
 * Class Select
 * @package CubeSystems\Leaf\Admin\Widgets
 */
class Select implements Renderable
{
    /**
     * @var array|Collection
     */
    protected $options;

    /**
     * @var string
     */
    protected $selected;

    /**
     * @var \CubeSystems\Leaf\Html\Elements\Inputs\Select
     */
    protected $element;

    /**
     * Select constructor.
     */
    public function __construct()
    {
        $this->element = Html::select();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @param $name
     * @return Select
     */
    public function name( $name )
    {
        $this->element->setName( $name );

        return $this;
    }

    /**
     * @param $options
     * @return Select
     */
    public function options( $options )
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param $value
     * @return Select
     */
    public function selected( $value )
    {
        $this->selected = $value;

        return $this;
    }

    /**
     * @return \CubeSystems\Leaf\Html\Elements\Inputs\Select
     */
    public function render()
    {
        foreach( $this->options as $key => $title )
        {
            $option = Html::option( (string) $title )->setValue( $key );

            if( (string) $this->selected === (string) $key )
            {
                $option->select();
            }

            $this->element->append( $option );
        }

        return $this->element;
    }

    /**
     * @return Select
     */
    public static function create()
    {
        return new static;
    }
}
