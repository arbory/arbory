<?php

namespace Arbory\Base\Admin\Form\Fields;

use App;
use Arbory\Base\Admin\Form\Fields\Renderer\TranslatableFieldRenderer;
use Arbory\Base\Admin\Form\FieldSet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Waavi\Translation\Models\Language;
use Waavi\Translation\Repositories\LanguageRepository;

/**
 * Class Translatable.
 */
class Translatable extends AbstractField implements ProxyFieldInterface
{
    /**
     * @var array
     */
    protected $locales = [];

    /**
     * @var string
     */
    protected $currentLocale;

    protected string $style = 'raw';

    protected string $rendererClass = TranslatableFieldRenderer::class;

    /**
     * Translatable constructor.
     *
     * @param FieldInterface $field
     */
    public function __construct(protected FieldInterface $field)
    {
        /** @var LanguageRepository $languages */
        $languages = App::make(LanguageRepository::class);
        $this->currentLocale = App::getLocale();

        $this->locales = $languages->all()->map(fn (Language $language) => $language->locale)->toArray();

        parent::__construct('translations');
    }

    public function getModel(): Model
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
            $this->getNameSpacedName() . '.' . $locale
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
     * @param $locale
     * @return Model|null
     * @see \Arbory\Base\Http\Controllers\Admin\SettingsController::getField
     *
     */
    public function getDefaultResourceForLocale($locale)
    {
        $resource = null;

        if ($this->getValue() && ! $this->getValue()->isEmpty()) {
            foreach ($this->getValue() as $item) {
                if ($item->{$this->getModel()->getLocaleKey()} === $locale) {
                    $resource = $item;
                }
            }
        }

        return $resource;
    }

    public function beforeModelSave(Request $request): void
    {
        foreach ($this->locales as $locale) {
            foreach ($this->getTranslatableResource($locale)->getFields() as $field) {
                $field->beforeModelSave($request);
            }
        }
    }

    public function afterModelSave(Request $request)
    {
        foreach ($this->locales as $locale) {
            foreach ($this->getTranslatableResource($locale)->getFields() as $field) {
                $field->afterModelSave($request);
            }
        }
    }

    public function getRules(): array
    {
        $rules = [];

        $translationsClass = $this->getModel()->getTranslationModelName();

        foreach ($this->getLocaleFieldSet(new $translationsClass, '*')->getFields() as $field) {
            $rules = array_merge($rules, $field->getRules());
        }

        return $rules;
    }

    public function getFieldTypeName(): string
    {
        return $this->field->getFieldTypeName();
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }
}
