<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\FieldSet;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

/**
 * Class HasOne
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class HasOne extends AbstractRelationField
{
    /**
     * @return Element
     */
    public function render()
    {
        $item = $this->getValue() ?: $this->getRelatedModel();

        $block = Html::div()->addClass( 'section content-fields' );

        foreach( $this->getRelationFieldSet( $item )->getFields() as $field )
        {
            $block->append( $field->render() );
        }

        return $block;
    }

    /**
     * @param Model $relatedModel
     * @return FieldSet
     */
    public function getRelationFieldSet( Model $relatedModel )
    {
        $fieldSet = new FieldSet( $relatedModel, $this->getNameSpacedName() );
        $fieldSetCallback = $this->fieldSetCallback;
        $fieldSetCallback( $fieldSet );

        $fieldSet->add( new Hidden( $relatedModel->getKeyName() ) )
            ->setValue( $relatedModel->getKey() );

        return $fieldSet;
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {

    }


    /**
     * @param Request $request
     */
    public function afterModelSave( Request $request )
    {
        $variables = $request->input( $this->getNameSpacedName() );

        if( !$variables )
        {
            return;
        }

        $relation = $this->getRelation();
        $relatedModel = $this->getValue() ?: $this->getRelatedModel();

        if( $relation instanceof MorphTo )
        {
            $relatedModel->fill( $variables );
            $relatedModel->save();

            $this->getModel()->fill( [
                $relation->getMorphType() => get_class( $relatedModel ),
                $relation->getForeignKey() => $relatedModel->{$relatedModel->getKeyName()},
            ] )->save();
        }
        elseif( $relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne )
        {
            $variables[$relation->getPlainForeignKey()] = $this->getModel()->getKey();

            $relatedModel->fill( $variables );
            $relatedModel->save();
        }
    }

}
