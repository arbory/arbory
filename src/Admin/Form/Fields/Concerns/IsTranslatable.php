<?php

namespace Arbory\Base\Admin\Form\Fields\Concerns;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Services\FieldTypeRegistry;
use Arbory\Base\Admin\Form\Fields\Translatable;

trait IsTranslatable
{
    /**
     * Set the field as translatable.
     *
     * @return Translatable|FieldInterface
     */
    public function translatable(): Translatable|FieldInterface
    {
        /** @var FieldTypeRegistry $registry */
        $registry = app(FieldTypeRegistry::class);

        $translatable = $registry->resolve('translatable', [clone $this]);

        /**
         * @var FieldSet
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
