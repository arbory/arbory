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
     * Link constructor.
     */
    public function __construct()
    {
        $this->element = Html::button();
        $this->element->addClass( 'button ');
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
        $this->element->addAttributes( [ 'type' => $inputType ] );

        if( $visualType )
        {
            $this->element->addClass( $visualType );
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
     * @return Button
     */
    public static function create( )
    {
        return new static;
    }

}
