<?php

namespace Arbory\Base\Services;

use Illuminate\Support\Collection;
use Arbory\Base\Admin\Form\FieldSet;
use Waavi\Translation\Models\Language;
use Arbory\Base\Admin\Form\Fields\Link;
use Arbory\Base\Admin\Form\Fields\HasMany;
use Arbory\Base\Admin\Form\Fields\Translatable;
use Arbory\Base\Admin\Form\Fields\AbstractField;
use Waavi\Translation\Repositories\LanguageRepository;
use Arbory\Base\Admin\Form\Fields\NestedFieldInterface;
use Arbory\Base\Admin\Form\Fields\RepeatableNestedFieldInterface;

class FieldSetFieldFinder
{
    /**
     * @var FieldSet
     */
    protected $fieldSet;

    /**
     * @var AbstractField
     */
    protected $initialField;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var LanguageRepository
     */
    protected $languageRepository;

    /**
     * @param LanguageRepository $languageRepository
     * @param FieldSet $fieldSet
     * @param AbstractField|null $initialField
     */
    public function __construct(
        LanguageRepository $languageRepository,
        FieldSet $fieldSet,
        $initialField = null
    ) {
        $this->languageRepository = $languageRepository;
        $this->fieldSet = $fieldSet;
        $this->initialField = $initialField;
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function contains(string $attribute): bool
    {
        $names = $this->getActualFieldNames($attribute);

        $found = $this->find($attribute);

        foreach ($names as $name) {
            if (! array_key_exists($name, $found)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $attribute
     * @return array
     */
    public function find(string $attribute)
    {
        /**
         * @var FieldSet
         * @var AbstractField $previousField
         */
        $previousFieldSet = $this->fieldSet;
        $previousField = $this->initialField;
        $fields = [];
        $inputNameParts = explode('.', $attribute);

        if ($this->initialField) {
            $fields = [$this->initialField->getName() => $this->initialField];
        }

        foreach ($inputNameParts as $index => $fieldName) {
            $field = null;

            if (! $previousFieldSet) {
                break;
            }

            /**
             * @var FieldSet
             * @var Collection $matchingFields
             */
            $matchingFields = $previousFieldSet->getFieldsByName($fieldName);

            if ($matchingFields->count() > 0) {
                if ($matchingFields->count() === 1) {
                    $field = $matchingFields->get(0);
                } else {
                    $field = $this->resolveMultipleFields(
                        $matchingFields,
                        substr($attribute, strpos($attribute, $fieldName) + strlen($fieldName) + 1, strlen($attribute))
                    );
                }
            }

            if (! $field && $previousField) {
                $previousFieldSet = $this->resolveFieldSet($previousField, $fieldName);
            } else {
                if ($field instanceof Link) {
                    $previousFieldSet = $field->getRelationFieldSet($previousField->getModel());
                }
            }

            if ($field) {
                $previousField = $field;

                $resolvedFieldSet = $this->resolveFieldSet($previousField, $fieldName);
                $previousFieldSet = $resolvedFieldSet ?? $previousFieldSet;

                $fields[$fieldName] = $field;
            }
        }

        return $fields;
    }

    /**
     * @param string $attribute
     * @return array
     */
    protected function getActualFieldNames($attribute)
    {
        $parts = explode('.', $attribute);
        $locales = $this->languageRepository->all()->map(function (Language $language) {
            return $language->locale;
        })->toArray();

        foreach ($parts as $index => $part) {
            if (is_numeric($part) || in_array($part, $locales, false)) {
                unset($parts[$index]);
            }
        }

        return $parts;
    }

    /**
     * @param Collection $fields
     * @param string $attribute
     * @return AbstractField|null
     */
    protected function resolveMultipleFields($fields, $attribute)
    {
        $matching = null;

        foreach ($fields->all() as $field) {
            /** @var AbstractField $field */
            $nameParts = explode('.', $attribute);
            $fieldName = reset($nameParts);

            $fieldSet = $this->resolveFieldSet($field, $fieldName);

            if (! $fieldSet) {
                continue;
            }

            $finder = new self($this->languageRepository, $fieldSet, $field);

            array_shift($nameParts);

            if ($finder->contains(implode('.', $nameParts))) {
                $matching = $field;
                break;
            }
        }

        return $matching;
    }

    /**
     * @param AbstractField $field
     * @param string $fieldName
     * @return FieldSet|null
     */
    protected function resolveFieldSet(AbstractField $field, string $fieldName)
    {
        if ($field instanceof RepeatableNestedFieldInterface) {
            /** @var HasMany $field */
            $nested = $field->getValue();

            if ($nested) {
                $resource = method_exists($nested, 'getModel') ? $nested->getModel() : $nested->get($fieldName);

                if (! $resource) {
                    return;
                }

                /*
                 * @var Collection $nested
                 * @var FieldSet $fieldSet
                 */
                return $field->getRelationFieldSet($resource, $fieldName);
            }
        } elseif ($field instanceof NestedFieldInterface) {
            if ($field->getValue()) {
                return $field->getNestedFieldSet($field->getValue());
            }

            return $field->getNestedFieldSet($field->getRelatedModel());
        } elseif ($field instanceof Translatable) {
            /** @var Translatable $field */
            if (! in_array($fieldName, $field->getLocales(), true)) {
                return;
            }

            return $field->getLocaleFieldSet(
                $field->getModel()->translateOrNew($fieldName),
                $fieldName
            );
        }
    }
}
