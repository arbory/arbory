<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\Slug;
use Arbory\Base\Admin\Form\Fields\Translatable;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

/**
 * Class TranslatableFieldRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class TranslatableFieldRenderer
{
    /**
     * @var Translatable
     */
    protected $field;

    /**
     * TranslatableFieldRenderer constructor.
     * @param Translatable $field
     */
    public function __construct( Translatable $field )
    {
        $this->field = $field;
    }

    /**
     * @param $locale
     * @return Element
     */
    protected function getLocalizedField( $locale )
    {
        $resource = $this->field->getTranslatableResource( $locale );

        return $resource->getFields()->first()->render();
    }

    /**
     * @param $locale
     * @return Element
     */
    protected function getLocalizedFieldContent( $locale )
    {
        $fieldContent = $this->getLocalizedField( $locale );
        $fieldContent->attributes()->put( 'class', 'localization' );
        $fieldContent->attributes()->put( 'data-locale', $locale );

        if( $this->field->getCurrentLocale() === $locale )
        {
            $fieldContent->addClass( 'active' );
        }

        return $fieldContent;
    }

    /**
     * @return Element
     */
    protected function getLocalizationMenu()
    {
        $list = Html::ul();

        foreach( $this->field->getLocales() as $locale )
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
    protected function getLocalizationSwitch()
    {
        $button = Html::button();
        $button->addClass( 'trigger' );
        $button->attributes()->put( 'type', 'button' );

        $trigger = $button->append(
            Html::span( $this->field->getCurrentLocale() )->addClass( 'label' )
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
     * @return Element
     */
    public function render()
    {
        $block = Html::div()->addClass( 'field i18n' );
        $block->addClass($this->field->getFieldTypeName());

        foreach( $this->field->getLocales() as $locale )
        {
            $block->append( $this->getLocalizedFieldContent( $locale ) );
        }

        $block->append( $this->getLocalizationSwitch() );

        return $block;
    }
}
