<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

/**
 * Class Select
 * @package Arbory\Base\Admin\Widgets
 */
class Select implements Renderable
{
    /**
     * @var array|Collection
     */
    protected $options;

    /**
     * @var array
     */
    protected $selected = [];

    /**
     * @var \Arbory\Base\Html\Elements\Inputs\Select
     */
    protected $element;

    /**
     * @var array
     */
    protected $attributes = [];

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
     * @return self
     */
    public function name( $name )
    {
        $this->element->setName( $name );

        return $this;
    }

    /**
     * @param $options
     * @return self
     */
    public function options( $options )
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param mixed $value
     * @return self
     */
    public function selected( $value )
    {
        $this->selected = (array) $value;

        return $this;
    }

    /**
     * @param array $attributes
     * @return self
     */
    public function attributes( array $attributes )
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return \Arbory\Base\Html\Elements\Inputs\Select
     */
    public function render()
    {
        foreach( $this->options as $key => $title )
        {
            $option = Html::option( (string) $title )->setValue( $key );

            if( in_array( $key, $this->selected ) )
            {
                $option->select();
            }

            $this->element->append( $option );
        }

        $this->element->addAttributes( $this->attributes );

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
