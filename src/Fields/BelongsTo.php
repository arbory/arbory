<?php

namespace CubeSystems\Leaf\Fields;

/**
 * Class BelongsTo
 * @package CubeSystems\Leaf\Fields
 */
class BelongsTo extends AbstractField
{
    /**
     * @return string
     */
    public function getDisplayField()
    {
        return 'name'; // TODO:
    }

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
                $this->getFieldSet()->getController()->getSlug(),
                $model->{$model->getKeyName()},
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
            $this->setValue( $this->getValue()->{$relatedModel->getKeyName()} );
        }

        return view( $this->getViewName(), [
            'field' => $this,
            'items' => $this->getRelatedModelOptions( $relatedModel ),
        ] );
    }

    /**
     * @return \Eloquent
     */
    protected function getRelatedModel()
    {
        $resource = $this->getFieldSet()->getResource();
        $model = new $resource;

        return $model->{$this->getName()}()->getRelated();
    }

    /**
     * @param \Eloquent $relatedModel
     * @return array
     */
    protected function getRelatedModelOptions( $relatedModel )
    {
        /**
         * @var $identifier string
         */
        $items = [ ];

        $keyName = $relatedModel->getKeyName();

        foreach( $relatedModel::all() as $item )
        {
            $identifier = $item->{$keyName};
            $items[$identifier] = $item->{$this->getDisplayField()};
        }

        return $items;
    }
}
