<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\CRUD\Resource;
use CubeSystems\Leaf\FieldSet;
use CubeSystems\Leaf\Html\Elements\Content;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use Dimsav\Translatable\Translatable as TranslatableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
        $this->currentLocale = app()->make( 'translator' )->getLocale();

        parent::__construct( 'translations' );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getListFieldOutput();
    }

    /**
     * @return TranslatableModel|Model
     */
    public function getModel()
    {
        return parent::getModel();
    }

    /**
     * @return Element|string
     */
    public function render()
    {
        $fields = new Content;

        foreach( $this->locales as $locale )
        {
            $fields->push( $this->getLocalizedFieldContent( $locale ) );
        }

        return Html::div()
            ->addClass( 'field type-text i18n' )// TODO: Get field type
            ->append( $fields )
            ->append( $this->getLocalizationSwitch() );
    }

    /**
     * @param $locale
     * @return Element
     */
    private function getLocalizedFieldContent( $locale )
    {
        $resource = $this->getTranslatableResource( $locale );

        $field = $resource->getFields()->first();
        $fieldContent = $field->render();

        $fieldContent->attributes()->put( 'class', 'localization' );
        $fieldContent->attributes()->put( 'data-locale', $locale );

        if( $this->currentLocale === $locale )
        {
            $fieldContent->addClass( 'active' );
        }

        return $fieldContent;
    }

    /**
     * @param $locale
     * @return Resource
     */
    private function getTranslatableResource( $locale )
    {
        return new Resource(
            $this->getModel()->translateOrNew( $locale ),
            new FieldSet( [ $this->field ] ),
            $this->getNameSpacedName()
        );
    }

    /**
     * @return Element
     */
    private function getLocalizationMenu()
    {
        $list = Html::ul();

        foreach( $this->locales as $locale )
        {
            $button = Html::button( $locale );
            $button->attributes()->put( 'name', 'button' );
            $button->attributes()->put( 'type', 'button' );
            $button->attributes()->put( 'data-locale', $locale );

            $list->append( Html::li( $button ) );
        }

        $localizationMenu = Html::menu( $list );
        $localizationMenu->attributes()->put( 'class', 'localization-menu-items' );
        $localizationMenu->attributes()->put( 'type', 'toolbar' );

        return $localizationMenu;
    }

    /**
     * @return Element
     */
    private function getLocalizationSwitch()
    {
        $button = Html::button();
        $button->addClass( 'trigger' );
        $button->attributes()->put( 'type', 'button' );

        $trigger = $button->append(
            Html::span($this->currentLocale)->addClass( 'label' )
        );
        $trigger->append(
            Html::i()->addClass( 'fa fa-chevron-down' )
        );

        $localizationSwitch = Html::div()
            ->addClass( 'localization-switch' )
            ->append( $trigger )
            ->append( $this->getLocalizationMenu() );

        return $localizationSwitch;
    }

    /**
     * @return Element|string
     */
    protected function getListFieldOutput()
    {
        $resource = $this->getTranslatableResource( $this->currentLocale );

        return (string) $resource->getFields()->first();
    }

    /**
     * @param Model|TranslatableModel $model
     * @param array $input
     */
    public function beforeModelSave( Request $request )
    {
        $inputVariables = array_get( $input, $this->getName() );
        $model = $this->getModel();

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
    public function afterModelSave( Request $request )
    {
        $inputVariables = array_get( $input, $this->getName() );

        foreach( $this->locales as $locale )
        {
            $value = array_get( $inputVariables, $locale . '.' . $this->field->getName(), [] );

            $this->field->afterModelSave( $model->translateOrNew( $locale ), [
                $this->field->getName() => $value
            ] );
        }
    }
}
