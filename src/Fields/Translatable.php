<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\FieldSet;
use Illuminate\Database\Eloquent\Model;

class Translatable extends AbstractRelationField
{
    private $fieldTitle = null;
    private $locales = null;

    public function __construct( callable $fieldSetCallback, $fieldTitle = null, $locales = null )
    {
        parent::__construct( 'translations', $fieldSetCallback );
        $this->fieldTitle = $fieldTitle;

        $this->locales = $locales ?: config( 'translatable.locales' );
    }

    public function canRemoveRelationItems()
    {
        return false;
    }

    public function canAddRelationItem()
    {
        return false;
    }

    public function render()
    {
        $fieldSet = $this->getRelationFieldSet();

        $relations = [];

        $translations = [];
        $value = $this->getValue();
        foreach( $value as $translation )
        {
            $translations[$translation->locale] = $translation;
        }

        $model = $this->getModel();
        /* @var $model \Dimsav\Translatable\Translatable */

        foreach( $this->locales as $locale )
        {
            $translation = isset( $translations[$locale] )
                ? $translations[$locale]
                : $model->getNewTranslation( $locale );

            $relations[$translation->locale] = $this->buildRelationForm(
                $translation,
                clone $fieldSet,
                $this->getName() . '_attributes.' . $translation->locale
            )->build();
        }

        /** @noinspection PhpUndefinedFieldInspection */

        return view( $this->getViewName(), [
            'field' => $this,
            'field_title' => $this->fieldTitle,
            'relations' => $relations,
            'template' => $this->getRelationFromTemplate( $this->getTranslatableModel(), clone $fieldSet )
        ] );
    }

    /**
     * @param $relatedModel
     * @param $fieldSet
     * @return string
     * @throws \Exception
     * @throws \Throwable
     */
    protected function getRelationFromTemplate( $relatedModel, $fieldSet )
    {
        $formBuilder = $this->buildRelationForm(
            $relatedModel,
            clone $fieldSet,
            $this->getName() . '_attributes' . '._template_'
        );

        return view( $this->getViewName() . '_fieldset', [
            'name' => $this->getName(),
            'index' => '_template_',
            'fields' => $formBuilder->build()->getFields()
        ] )->render();
    }

    protected function getRelatedModel( $model )
    {
        return $model->{$this->getName()}();
    }

    /**
     * @param Model $model
     * @param array $input
     * @return void
     */
    public function postUpdate( Model $model, array $input = [] )
    {
        /**
         * @var $translationsRelation \Illuminate\Database\Eloquent\Relations\HasMany
         */
        $inputVariables = array_get( $input, $this->getName() . '_attributes' );

        if( !$inputVariables )
        {
            return;
        }
        /* @var $model \Dimsav\Translatable\Translatable */

        $relation = $model->translations();
        $relationForeignKey = $relation->getPlainForeignKey();
        $relationForeignKeyValue = $model->getKey();

        foreach( $inputVariables as $locale => $translationVariables )
        {
            $translationModel = $model->translateOrNew( $locale );

            $translationVariables[$relationForeignKey] = $relationForeignKeyValue;
            $translationModel->fill( $translationVariables );
            $translationModel->save();
        }
    }

    public function getRelationFieldSet()
    {
        $fieldSet = new FieldSet( get_class( $this->getTranslatableModel() ), $this->getFieldSet()->getController() );
        $fieldSetCallback = $this->fieldSetCallback;
        $fieldSetCallback( $fieldSet );

        return $fieldSet;
    }

    private function getTranslatableModel()
    {
        $modelClass = $this->getFieldSet()->getResource();
        $model = new $modelClass;

        $translatableModelClass = $model->translatableModel ?: $modelClass . 'Translation';

        return new $translatableModelClass;
    }
}
