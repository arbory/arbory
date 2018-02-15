<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Concerns\HasRelationships;
use Arbory\Base\Admin\Form\Fields\Renderer\OptionFieldRenderer;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class BelongsTo
 * @package Arbory\Base\Admin\Form\Fields
 */
class BelongsTo extends AbstractField
{
    use HasRelationships;

    /**
     * @return Element
     */
    public function render()
    {
        $renderer = new OptionFieldRenderer( $this );
        $foreignKey = $this->getRelation()->getForeignKey();
        $foreignId = $this->getModel()->getAttribute( $foreignKey );

        $renderer->setSelected( $foreignId );

        return $renderer->render();
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
