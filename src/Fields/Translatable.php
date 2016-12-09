<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Builder\FormBuilder;
use CubeSystems\Leaf\FieldSet;
use Dimsav\Translatable\Translatable as TranslatableModel;
use Illuminate\Database\Eloquent\Model;

class Translatable extends AbstractField
{
    /**
     * @var FieldInterface
     */
    private $field;

    /**
     * @var array
     */
    private $locales = [];

    /**
     * Translatable constructor.
     * @param FieldInterface $field
     */
    public function __construct( FieldInterface $field )
    {
        $this->field = $field;
        $this->locales = (array) config( 'translatable.locales' );

        parent::__construct( 'translations' );
    }

    /**
     * @return TranslatableModel|Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render( array $attributes = [] )
    {
        switch( $this->getContext() )
        {
            case static::CONTEXT_FORM:
                return $this->getFormFieldOutput( $attributes );
                break;

            case static::CONTEXT_LIST:
                return $this->getListFieldOutput( $attributes );
                break;
        }
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function getFormFieldOutput( array $attributes = [] )
    {
        $model = $this->getModel();

        $fields = [];

        foreach( $this->locales as $locale )
        {
            $field = clone $this->field;
            $field->setInputNamespace( $this->getName() . '_attributes.' . $locale );

            $localizationModel = $model->translateOrNew( $locale );

            $fieldSet = new FieldSet;
            $fieldSet->add( $field );

            $builder = new FormBuilder( $localizationModel );
            $builder->setFieldSet( $fieldSet );

            $fields[$locale] = $builder->build()->first();
        }

        return view( $this->getViewName(), [
            'name' => $this->field->getName(),
            'locales' => $this->locales,
            'locale' => app()->make('translator')->getLocale(),
            'fields' => $fields,
            'attributes' => $attributes,
        ] );
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    protected function getListFieldOutput( array $attributes = [] )
    {
        // TODO: Move this functionality outside

        $model = $this->getModel();

        $field = clone $this->field;
        $field->setListContext();
        $field->setModel( $model );
        $field->setFieldSet( $this->getFieldSet() );
        $field->setController( $this->getController() );

        return $field->render( $attributes );
    }

    /**
     * @param Model|TranslatableModel $model
     * @param array $input
     */
    public function beforeModelSave( Model $model, array $input = [] )
    {
        $inputVariables = array_get( $input, $this->getName() . '_attributes' );

        foreach( $this->locales as $locale )
        {
            $value = array_get( $inputVariables, $locale . '.' . $this->field->getName(), [] );

            $translationModel = $model->translateOrNew( $locale );
            $translationModel->setAttribute( $this->field->getName(), $value );

            $this->field->beforeModelSave( $translationModel, [
                $this->field->getName() => $value
            ] );
        }
    }

    /**
     * @param Model|TranslatableModel $model
     * @param array $input
     */
    public function afterModelSave( Model $model, array $input = [] )
    {
        $inputVariables = array_get( $input, $this->getName() . '_attributes' );

        foreach( $this->locales as $locale )
        {
            $value = array_get( $inputVariables, $locale . '.' . $this->field->getName(), [] );

            $this->field->afterModelSave( $model->translateOrNew( $locale ), [
                $this->field->getName() => $value
            ] );
        }
    }
}
