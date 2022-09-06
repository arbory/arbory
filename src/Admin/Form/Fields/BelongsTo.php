<?php

namespace Arbory\Base\Admin\Form\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRelationships;
use Arbory\Base\Admin\Form\Fields\Renderer\SelectFieldRenderer;

/**
 * Class BelongsTo.
 */
class BelongsTo extends Select
{
    use HasRelationships;

    public function getValue()
    {
        $foreignKey = $this->getRelation()->getForeignKeyName();
        $value = $this->getModel()->getAttribute($foreignKey);

        return $value ?? $this->getDefaultValue();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function getOptions(): Collection
    {
        return $this->getRelatedItems();
    }

    public function beforeModelSave(Request $request): void
    {
        $this->getModel()->setAttribute(
            $this->getRelation()->getForeignKeyName(),
            $request->input($this->getNameSpacedName())
        );
    }
}
