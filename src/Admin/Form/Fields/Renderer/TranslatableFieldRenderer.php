<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Form\Fields\Translatable;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

/**
 * Class TranslatableFieldRenderer.
 */
class TranslatableFieldRenderer implements RendererInterface
{
    /**
     * @var Translatable
     */
    protected $field;

    /**
     * TranslatableFieldRenderer constructor.
     * @param Translatable $field
     */
    public function __construct(Translatable $field)
    {
        $this->field = $field;
    }

    /**
     * @param $locale
     * @return FieldInterface
     */
    protected function getLocalizedField($locale)
    {
        $resource = $this->field->getTranslatableResource($locale);

        return $resource->getFields()->first();
    }

    /**
     * @param $locale
     * @return Element
     */
    protected function getLocalizedFieldContent($locale)
    {
        $field = $this->getLocalizedField($locale);

        $styleManager = $this->field->getFieldSet()->getStyleManager();
        $options = $styleManager->newOptions();

        $options->addAttributes(
            ['data-locale' => $locale]
        )->addClass('localization');

        if ($this->field->getCurrentLocale() === $locale) {
            $options->addClass('active');
        }

        $block = $styleManager->render($field->getStyle() ?: $styleManager->getDefaultStyle(), $field, $options);

        return $block;
    }

    /**
     * @return Element
     */
    protected function getLocalizationMenu()
    {
        $list = Html::ul();

        foreach ($this->field->getLocales() as $locale) {
            $button = Html::button($locale);
            $button->attributes()->put('name', 'button');
            $button->attributes()->put('type', 'button');
            $button->attributes()->put('data-locale', $locale);

            $list->append(Html::li($button));
        }

        $localizationMenu = Html::menu($list);
        $localizationMenu->attributes()->put('class', 'localization-menu-items');
        $localizationMenu->attributes()->put('type', 'toolbar');

        return $localizationMenu;
    }

    /**
     * @return Element
     */
    protected function getLocalizationSwitch()
    {
        $button = Html::button();
        $button->addClass('trigger');
        $button->attributes()->put('type', 'button');

        $trigger = $button->append(
            Html::span($this->field->getCurrentLocale())->addClass('label')
        );
        $trigger->append(
            Html::i('arrow_drop_down')->addClass('mt-icon')
        );

        $localizationSwitch = Html::div()
            ->addClass('localization-switch')
            ->append($trigger)
            ->append($this->getLocalizationMenu());

        return $localizationSwitch;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $block = Html::div()->addClass('field i18n');
        $block->addClass($this->field->getFieldTypeName());

        foreach ($this->field->getLocales() as $locale) {
            $block->append($this->getLocalizedFieldContent($locale));
        }

        $block->append($this->getLocalizationSwitch());

        return $block;
    }

    /**
     * @param FieldInterface $field
     *
     * @return mixed
     */
    public function setField(FieldInterface $field): RendererInterface
    {
        $this->field = $field;

        return $field;
    }

    /**
     * @return FieldInterface
     */
    public function getField(): FieldInterface
    {
        return $this->field;
    }

    /**
     * Configure the style before rendering the field.
     *
     * @param StyleOptionsInterface $options
     *
     * @return StyleOptionsInterface
     */
    public function configure(StyleOptionsInterface $options): StyleOptionsInterface
    {
        return $options;
    }
}
