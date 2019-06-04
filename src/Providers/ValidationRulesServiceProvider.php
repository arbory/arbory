<?php

namespace Arbory\Base\Providers;

use Illuminate\Http\Request;
use Arbory\Base\Admin\Form\FieldSet;
use Illuminate\Support\ServiceProvider;
use Arbory\Base\Admin\Form\Fields\HasMany;
use Arbory\Base\Admin\Form\Fields\Translatable;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class ValidationRulesServiceProvider extends ServiceProvider
{
    /**
     * @var ValidationFactory
     */
    protected $validator;

    /**
     * @param ValidationFactory $validator
     */
    public function boot(ValidationFactory $validator)
    {
        $this->validator = $validator;

        $this->registerValidationRules();
    }

    /**
     * Register validation rules.
     */
    private function registerValidationRules()
    {
        $this->registerFileRequiredRule();
        $this->registerRequireOneLocalizationRule();
    }

    /**
     * @param Request $request
     * @param $attribute
     * @return bool
     */
    private function isDestroyed(Request $request, $attribute)
    {
        $fieldSet = $request->get('fields');
        $fields = $fieldSet->findFieldsByInputName($attribute);
        $fields = array_reverse($fields);

        foreach ($fields as $fieldName => $field) {
            if ($field instanceof HasMany) {
                $attributeParts = explode('.', $attribute);
                $toManyIndex = array_search($fieldName, $attributeParts, true);
                $attributeParent = array_slice($attributeParts, 0, $toManyIndex + 2);
                $attributeParent = implode('.', $attributeParent);

                $isDestroyed = array_get($request->input($attributeParent), '_destroy');

                return filter_var($isDestroyed, FILTER_VALIDATE_BOOLEAN);
            }
        }

        return false;
    }

    protected function registerFileRequiredRule()
    {
        $this->validator->extendImplicit('arbory_file_required', function ($attribute) {
            /** @var FieldSet $fields */
            $request = \request();
            $fields = $request->get('fields');
            $field = $fields->findFieldByInputName($attribute);
            $file = $request->file($attribute);

            if ($this->isDestroyed($request, $attribute)) {
                return true;
            }

            if (! $field) {
                return (bool) $file;
            }

            return $field->getValue() || $file;
        });
    }

    protected function registerRequireOneLocalizationRule()
    {
        $this->validator->extendImplicit('arbory_require_one_localized', function ($attribute, $value) {
            /** @var FieldSet $fieldSet */
            $request = \request();
            $fieldSet = $request->request->get('fields');
            $fields = $fieldSet->findFieldsByInputName($attribute);
            $translatable = null;

            if ($this->isDestroyed($request, $attribute)) {
                return true;
            }

            foreach (array_reverse($fields) as $index => $field) {
                if ($field instanceof Translatable) {
                    $translatable = $field;
                }
            }

            if (! $translatable || $value) {
                return (bool) $value;
            }

            $attributeLocale = null;
            $checkLocales = $translatable->getLocales();

            foreach ($checkLocales as $index => $checkLocale) {
                if (str_contains($attribute, $checkLocale)) {
                    $attributeLocale = $checkLocale;
                    unset($checkLocales[$index]);
                    break;
                }
            }

            foreach ($checkLocales as $index => $checkLocale) {
                $checkByAttribute = str_replace($attributeLocale, $checkLocale, $attribute);
                $field = $fieldSet->findFieldByInputName($checkByAttribute);

                $valueNotNull = ($field->getValue() && $request->input($checkByAttribute) !== null);

                if ($request->input($checkByAttribute) || $valueNotNull) {
                    return true;
                }
            }

            return false;
        });
    }
}
