<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class HasRelationships
 * @package CubeSystems\Leaf\Admin\Form\Fields\Concerns
 */
trait HasRelationships
{
    /**
     * @return Relation|BelongsTo|BelongsToMany|HasOne|HasMany|MorphOne|MorphMany|MorphTo
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
     * @return Collection|Model[]
     */
    protected function getRelatedItems()
    {
        return $this->getRelatedModel()->all()->keyBy( $this->getRelatedModel()->getKeyName() );
    }
}
