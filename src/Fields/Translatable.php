<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Builder\FormBuilder;
use CubeSystems\Leaf\FieldSet;
use CubeSystems\Leaf\Html\Elements\Attributes;
use CubeSystems\Leaf\Html\Elements\Button;
use CubeSystems\Leaf\Html\Elements\Div;
use CubeSystems\Leaf\Html\Html;
use CubeSystems\Leaf\Html\Tag;
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
     */
    protected function getFormFieldOutput( array $attributes = [] )
    {
        $model = $this->getModel();

        $fields = [];

        $localizedFields = [];

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



//            $localizedFieldBlock = ( new Div( [] ) )->addClass( 'localization active' );
//            $localizedFieldBlock->attributes()->put('data-locale',$locale);
//
//            $localizedFields[] = $localizedFieldBlock;
        }




//        foreach( $this->locales as $locale )
//        {
//
//
//        }



//        $switch = ( new Div() )->addClass('localization-switch');

//        new Button();



//        Html::ul([
//            Html::li(
//                Html::button()->setName('button')->setContent('lv')
//            )
//            Html::li(
//                Html::button()->setName('button')->setContent('lv')
//            )
//        ]);





        dd( (string) Html::div()
            ->append( Html::div()
                ->append(Html::div('Sūds 1'))
            )
            ->append( Html::div(
                'Sūds 2'
//                Html::button()->setName('button')->setContent('en')
            )) );




        $languages = [];

        foreach( $this->locales as $locale )
        {
            $button = new Tag( 'button' );
            $button->setAttributes( new Attributes( [
                'name' => 'button',
                'data-locale' => $locale,
            ] ) );
            $button->setContent( $locale );

            $listElement = new Tag( 'li' );
            $listElement->setContent( $button );

            $languages[] = $listElement;
        }

        $list = new Tag( 'ul' );
        $list->setContent( $languages );

        $localizationMenu = new Tag( 'menu' );
        $localizationMenu->setAttributes( new Attributes( [
            'class' => 'localization-menu-items',
            'type' => 'toolbar',
        ] ) );
        $localizationMenu->setContent( $list );

        dd( (string) $localizationMenu );

//        return ( new Div( [
//            ( new Div( $input->label( $this->getLabel() ) ) )->addClass( 'label-wrap' ),
//            ( new Div( $input ) )->addClass( 'value' ),
//        ] ) )->addClass( 'field type-text i18n' );




//<div class="field type-text i18n" data-name="{{$name}}"> {{-- TODO: Field type --}}
//    @foreach($fields as $fieldLocale => $localizedField)
//        <div class="localization @if($fieldLocale===$locale) active @endif " data-locale="{{$fieldLocale}}">
//            {!! $localizedField->render() !!}
//        </div>
//    @endforeach
//    <div class="localization-switch">
//        <button name="button" type="button" title="Pārslēgt valodu" class="trigger">{{-- TODO: Translate title --}}
//            <span class="label">{{$locale}}</span>
//            <i class="fa fa-chevron-down"></i>
//        </button>
//        <menu class="localization-menu-items" type="toolbar">
//            <ul>
//    @foreach( $locales as $locale )
//                    <li>
//                        <button type="button" data-locale="{{$locale}}">{{$locale}}</button>
//                    </li>
//    @endforeach
//            </ul>
//        </menu>
//    </div>
//</div>






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
