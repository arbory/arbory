<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Concerns\HasRelatedOptions;
use Arbory\Base\Admin\Form\Fields\Renderer\SelectFieldRenderer;
use Illuminate\Http\Request;

/**
 * Class Dropdown
 * @package Arbory\Base\Admin\Form\Fields
 */
class Select extends AbstractField
{
    use HasRelatedOptions;

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    public function render()
    {
        return ( new SelectFieldRenderer( $this, $this->getOptions() ) )->render();
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {
        $property = $this->getName();
        $value = $request->has( $this->getNameSpacedName() )
            ? $request->input( $this->getNameSpacedName() )
            : null;

        if( !$this->options->has( $value ) )
        {
            throw new \RuntimeException( 'Bad select field value for "' . $this->getName() . '  "' );
        }

        if( method_exists( $this->getModel(), $this->getName() ) )
        {
            $property = $this->getRelation()->getForeignKey();
        }

        $this->getModel()->setAttribute( $property, $value );
    }
}
