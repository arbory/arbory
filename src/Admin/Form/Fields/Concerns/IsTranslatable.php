<?php


namespace Arbory\Base\Admin\Form\Fields\Concerns;

use Arbory\Base\Admin\Form\Fields\Translatable;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Services\FieldTypeRegistry;

trait IsTranslatable
{
    /**
     * Set the field as translatable
     *
     * @return Translatable|\Arbory\Base\Admin\Form\Fields\FieldInterface
     */
    public function translatable()
    {
        /**
         * @var $registry FieldTypeRegistry
         */
        $registry = app(FieldTypeRegistry::class);

        $translatable = $registry->resolve('translatable', [clone $this]);

        /**
         * @var $fieldSet FieldSet
         */
        $fieldSet = $this->getFieldSet();


        foreach ($fieldSet as $key => $field) {
            if ($field !== $this) {
                continue;
            }

            $fieldSet[$key] = $translatable;
        }

        return $translatable;
    }
}
