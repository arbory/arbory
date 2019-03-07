<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Button
 * @package Arbory\Base\Admin\Widgets
 */
class Button implements Renderable
{
    /**
     * @var Element
     */
    protected $element;

    /**
     * @var boolean
     */
    protected $iconOnly;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string|null $name
     * @param null $value
     */
    public function __construct( string $name = null, $value = null )
    {
        $this->name = $name;
        $this->value = $value;
        $this->element = Html::button();
        $this->element->addClass( 'button ' );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @param $title
     * @return Button
     */
    public function title( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param $name
     * @return Button
     */
    public function withIcon( $name )
    {
        $this->element->addClass( 'with-icon' );
        $this->element->append( Html::i()->addClass( 'fa fa-' . $name ) );

        return $this;
    }

    /**
     * @param $inputType
     * @param null $visualType
     * @return Button
     */
    public function type( $inputType, $visualType = null )
    {
        $attributes = [ 'type' => $inputType ];
        $attributes += array_filter( [ 'name' => $this->name, 'value' => $this->value ] );

        $this->element->addAttributes( $attributes );

        if( $visualType )
        {
            $this->element->addClass( $visualType );
        }

        return $this;
    }

    /**
     * @param bool $cache
     *
     * @return $this
     */
    public function asAjaxbox($cache = false) {
        $this->element->addClass('ajaxbox');

        if($cache) {
            $this->element->addAttributes(['data-cache' => 1]);
        }

        return $this;
    }

    /**
     * @return Button
     */
    public function iconOnly()
    {
        $this->iconOnly = true;

        return $this;
    }

    /**
     * @return Button
     */
    public function disableOnSubmit()
    {
        $this->element->addAttributes( [ 'data-disable' => 'true' ] );

        return $this;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $this->element->addAttributes( [ 'title' => $this->title ] );

        if( $this->iconOnly )
        {
            $this->element->addClass( 'only-icon' );
        }
        else
        {
            $this->element->append( $this->title  );
        }

        return $this->element;
    }

    /**
     * @param string|null $name
     * @param null $value
     * @return Button
     */
    public static function create( string $name = null, $value = null )
    {
        return new static( $name, $value );
    }
}
