<?php


namespace Arbory\Base\Admin\Form\Fields\Concerns;

use Arbory\Base\Admin\Form\Fields\Translatable;

trait IsTranslatable
{
    public function translatable()
    {
        $translatable = new Translatable(clone $this);

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
