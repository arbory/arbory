<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Renderer\OptionFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

/**
 * Class BelongsTo
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class BelongsTo extends AbstractField
{
    /**
     * @return Element
     */
    public function render()
    {
        return ( new OptionFieldRenderer( $this ) )->render();
    }

    /**
     * @return Relation
     */
    protected function getRelation()
    {
        return $this->getModel()->{$this->getName()}();
    }

    /**
     * @return Model
     */
    protected function getRelatedModel()
    {
        return $this->getRelation()->getRelated();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    protected function getRelatedItems()
    {
        return $this->getRelatedModel()->all()->keyBy( $this->getRelatedModel()->getKeyName() );
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
