<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Field
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class FieldRenderer implements Renderable
{
    protected $type;
    protected $name;
    protected $label;
    protected $value;

    /**
     * @param $type
     * @return $this
     */
    public function setType( $type )
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $label
     * @return $this
     */
    public function setLabel( $label )
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue( $value )
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Element
     */
    protected function build()
    {
        $template = Html::div()->addClass( 'field' );

        if( $this->type )
        {
            $template->addClass( 'type-' . $this->type );
        }

        if( $this->name )
        {
            $template->addAttributes( [
                'data-name' => $this->name
            ] );
        }

        if( $this->label )
        {
            $template->append( Html::div( $this->label )->addClass( 'label-wrap' ) );
        }

        if( $this->value )
        {
            $template->append( Html::div( $this->value )->addClass( 'value' ) );
        }

        return $template;
    }

    /**
     * @return Element
     */
    public function render()
    {
        return $this->build();
    }

}
