<?php

namespace Arbory\Base\Admin\Form\Fields;

use Illuminate\Http\Request;
use Arbory\Base\Admin\Form\FieldSet;
use Waavi\Translation\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Waavi\Translation\Repositories\LanguageRepository;
use Dimsav\Translatable\Translatable as TranslatableModel;
use Arbory\Base\Admin\Form\Fields\Renderer\TranslatableFieldRenderer;

/**
 * Class Translatable.
 */
class Translatable extends AbstractField implements ProxyFieldInterface
{
    /**
     * @var FieldInterface
     */
    protected $field;

    /**
     * @var array
     */
    protected $locales = [];

    /**
     * @var string
     */
    protected $currentLocale;

    protected $style = 'raw';

    protected $rendererClass = TranslatableFieldRenderer::class;

    /**
     * Translatable constructor.
     * @param FieldInterface $field
     */
    public function __construct(FieldInterface $field)
    {
        /** @var LanguageRepository $languages */
        $languages = \App::make(LanguageRepository::class);

        $this->field = $field;
        $this->currentLocale = \App::getLocale();

        $this->locales = $languages->all()->map(function (Language $language) {
            return $language->locale;
        })->toArray();

        parent::__construct('translations');
    }

    /**
     * @return TranslatableModel|Model
     */
    public function getModel()
    {
        return parent::getModel();
    }

    /**
     * @return array
     */
    public function getLocales()
    {
        return $this->locales;
    }

    /**
     * @param array $locales
     *
     * @return $this
     */
    public function setLocales(array $locales = [])
    {
        $this->locales = $locales;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    /**
     * @param $locale
     * @return FieldSet
     */
    public function getTranslatableResource($locale)
    {
        return $this->getLocaleFieldSet(
            $this->getModel()->translateOrNew($locale),
            $locale
        );
    }

    /**
     * @param $model
     * @param $locale
     * @return FieldSet
     */
    public function getLocaleFieldSet($model, $locale)
    {
        $fieldSet = new FieldSet(
            $model,
            $this->getNameSpacedName().'.'.$locale
        );

        $field = clone $this->field;
        $field->setFieldSet($fieldSet);
        $field->rules(implode('|', $this->rules));

        $defaultResource = $this->getDefaultResourceForLocale($locale);

        if ($defaultResource && ! $field->getValue()) {
            $field->setValue($defaultResource->{$field->getName()});
        }

        $fieldSet->getFields()->push($field);

        return $fieldSet;
    }

    /**
     * @see \Arbory\Base\Http\Controllers\Admin\SettingsController::getField
     *
     * @param $locale
     * @return Model|null
     */
    public function getDefaultResourceForLocale($locale)
    {
        $resource = null;

        if ($this->getValue() && ! $this->getValue()->isEmpty()) {
            foreach ($this->getValue() as $index => $item) {
                if ($item->{$this->getModel()->getLocaleKey()} === $locale) {
                    $resource = $item;
                }
            }
        }

        return $resource;
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave(Request $request)
    {
        foreach ($this->locales as $locale) {
            foreach ($this->getTranslatableResource($locale)->getFields() as $field) {
                $field->beforeModelSave($request);
            }
        }
    }

    /**
     * @param Request $request
     */
    public function afterModelSave(Request $request)
    {
        foreach ($this->locales as $locale) {
            foreach ($this->getTranslatableResource($locale)->getFields() as $field) {
                $field->afterModelSave($request);
            }
        }
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        $rules = [];

        $translationsClass = $this->getModel()->getTranslationModelName();

        foreach ($this->getLocaleFieldSet(new $translationsClass, '*')->getFields() as $field) {
            $rules = array_merge($rules, $field->getRules());
        }

        return $rules;
    }

    /**
     * @return string
     */
    public function getFieldTypeName()
    {
        return $this->field->getFieldTypeName();
    }

    /**
     * @return FieldInterface
     */
    public function getField(): FieldInterface
    {
        return $this->field;
    }
}
