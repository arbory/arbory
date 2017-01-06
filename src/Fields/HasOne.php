<?php

namespace CubeSystems\Leaf\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class HasOne
 * @package CubeSystems\Leaf\Fields
 */
class HasOne extends AbstractRelationField
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $fieldSet = $this->getRelationFieldSet();

        $item = $this->getValue();

        if( !$item )
        {
            $item = $this->getModel()->{$this->getName()}()->getRelated();
        }

        $relationForm = $this->buildRelationForm(
            $item,
            clone $fieldSet,
            $this->getName()
        )->build();

        return view( $this->getViewName(), [
            'field' => $this,
            'relation_fields' => $relationForm->getFields(),
        ] );
    }

    /**
     * @return bool
     */
    protected function canRemoveRelationItems()
    {
        return false;
    }

    /**
     * @param Model $model
     * @param array $input
     * @return void
     */
    public function afterModelSave( Model $model, array $input = [ ] )
    {
        /**
         * @var $relation \Illuminate\Database\Eloquent\Relations\HasMany|MorphOneOrMany
         * @var $relatedModel Model
         */

        $variables = array_get( $input, $this->getName() );

        if( !$variables )
        {
            return;
        }

        $relation = $model->{$this->getName()}();
        $relatedModel = $model->{$this->getName()} ?: $relation->getRelated();

        if( $relation instanceof MorphTo )
        {
            $relatedModel->fill( $variables );
            $relatedModel->save();

            $model->fill( [
                $relation->getMorphType() => get_class( $relatedModel ),
                $relation->getForeignKey() => $relatedModel->{$relatedModel->getKeyName()},
            ] )->save();
        }
        elseif( $relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne )
        {
            $variables[$relation->getPlainForeignKey()] = $model->getKey();

            $relatedModel->fill( $variables );
            $relatedModel->save();
        }
    }

}
