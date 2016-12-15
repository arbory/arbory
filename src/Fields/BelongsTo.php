<?php

namespace CubeSystems\Leaf\Fields;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BelongsTo
 * @package CubeSystems\Leaf\Fields
 */
class BelongsTo extends AbstractField
{
    /**
     * @return \Illuminate\View\View|null
     */
    public function render()
    {
        if( $this->isForList() )
        {
            return $this->renderListField();
        }
        elseif( $this->isForForm() )
        {
            return $this->renderFormField();
        }

        return null;
    }

    /**
     * @return \Illuminate\View\View
     */
    protected function renderListField()
    {
        $model = $this->getModel();

        return view( $this->getViewName(), [
            'field' => $this,
            'url' => route( 'admin.model.edit', [
                $this->getController()->getSlug(),
                $model->getKey(),
            ] ),
        ] );
    }

    /**
     * @return \Illuminate\View\View
     */
    protected function renderFormField()
    {
        $relatedModel = $this->getRelatedModel();

        if( $this->getValue() !== null )
        {
            $this->setValue( $this->getValue()->getKey() );
        }

        return view( $this->getViewName(), [
            'field' => $this,
            'items' => $this->getRelatedModelOptions( $relatedModel ),
        ] );
    }

    /**
     * @return Model
     */
    protected function getRelatedModel()
    {
        return $this->getModel()->{$this->getName()}()->getRelated();
    }

    /**
     * @param Model $relatedModel
     * @return array
     */
    protected function getRelatedModelOptions( $relatedModel )
    {
        /**
         * @var $identifier string
         */
        $items = [];

        foreach( $relatedModel::all() as $item )
        {
            $items[$item->getKey()] = (string) $item;
        }

        return $items;
    }
}
