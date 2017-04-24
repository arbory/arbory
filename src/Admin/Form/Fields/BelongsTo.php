<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Concerns\HasRelationships;
use CubeSystems\Leaf\Admin\Form\Fields\Renderer\OptionFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class BelongsTo
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class BelongsTo extends AbstractField
{
    use HasRelationships;

    /**
     * @return Element
     */
    public function render()
    {
        return ( new OptionFieldRenderer( $this ) )->render();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function getOptions()
    {
        return $this->getRelatedItems();
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {
        $this->getModel()->setAttribute(
            $this->getRelatedModel()->getForeignKey(),
            $request->input( $this->getNameSpacedName() )
        );
    }
}
