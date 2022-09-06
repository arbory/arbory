<?php

namespace Arbory\Base\Admin\Form\Fields;

use Illuminate\Http\Request;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRelationships;
use Arbory\Base\Admin\Form\Fields\Renderer\AssociatedSetRenderer;

/**
 * Class BelongsToMany.
 */
class BelongsToMany extends AbstractField
{
    use HasRelationships;

    protected string $rendererClass = AssociatedSetRenderer::class;

    /**
     * @return bool
     */
    public function isSortable()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isSearchable()
    {
        return false;
    }

    public function getValue(): mixed
    {
        $relatedModel = $this->getRelatedModel();

        return parent::getValue()->pluck($relatedModel->getKeyName())->all();
    }

    public function getOptions()
    {
        return $this->getRelatedItems();
    }

    public function beforeModelSave(Request $request): void
    {
    }

    public function afterModelSave(Request $request)
    {
        $relation = $this->getRelation();

        $submittedIds = $request->input($this->getNameSpacedName(), []);
        $existingIds = $this->getModel()->getAttribute($this->getName())
            ->pluck($this->getRelatedModel()->getKeyName())
            ->toArray();

        foreach ($existingIds as $id) {
            if (! in_array($id, $submittedIds)) {
                $relation->detach($id);
            }
        }

        foreach ($submittedIds as $id) {
            if (! in_array($id, $existingIds)) {
                $relation->attach($id);
            }
        }
    }
}
