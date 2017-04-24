<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use Closure;
use CubeSystems\Leaf\Admin\Form\Fields\Concerns\HasRelationships;
use CubeSystems\Leaf\Admin\Form\FieldSet;
use CubeSystems\Leaf\Admin\Form\Fields\Renderer\NestedFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class HasMany
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class HasMany extends AbstractField
{
    use HasRelationships;

    /**
     * @var Closure
     */
    protected $fieldSetCallback;

    /**
     * AbstractRelationField constructor.
     * @param string $name
     * @param Closure $fieldSetCallback
     */
    public function __construct( $name, Closure $fieldSetCallback )
    {
        parent::__construct( $name );

        $this->fieldSetCallback = $fieldSetCallback;
    }

    /**
     * @return bool
     */
    public function canAddRelationItem()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canRemoveRelationItems()
    {
        return true;
    }

    /**
     * @return Element|string
     */
    public function render()
    {
        return ( new NestedFieldRenderer( $this ) )->render();
    }

    /**
     * @param $index
     * @param Model $model
     * @return FieldSet
     */
    public function getRelationFieldSet( $model, $index )
    {
        $fieldSet = new FieldSet( $model, $this->getNameSpacedName() . '.' . $index );
        $fieldSetCallback = $this->fieldSetCallback;
        $fieldSetCallback( $fieldSet );

        $fieldSet->prepend(
            ( new Hidden( $model->getKeyName() ) )
                ->setValue( $model->getKey() )
        );

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
        $relationsInput = $request->input( $this->getNameSpacedName() );

        foreach( $relationsInput as $relationVariables )
        {
            $this->processRelationItemUpdate( $relationVariables );
        }
    }

    /**
     * @param array $variables
     */
    private function processRelationItemUpdate( array $variables )
    {
        $variables[$this->getRelation()->getForeignKeyName()] = $this->getModel()->getKey();

        $relatedModel = $this->findRelatedModel( $variables );

        if( filter_var( array_get( $variables, '_destroy' ), FILTER_VALIDATE_BOOLEAN ) )
        {
            $relatedModel->delete();

            return;
        }

        $relatedModel->fill( $variables );
        $relatedModel->save();
    }

    /**
     * @param $variables
     * @return Model
     */
    private function findRelatedModel( $variables )
    {
        $relation = $this->getRelation();

        $relatedModelId = array_get( $variables, $relation->getRelated()->getKeyName() );

        return $relation->getRelated()->findOrNew( $relatedModelId );
    }
}
