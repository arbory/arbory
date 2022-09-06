<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Concerns\HasNestedFieldSet;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRelationships;
use Arbory\Base\Admin\Form\FieldSet;
use Closure;

abstract class AbstractRelationField extends AbstractField implements NestedFieldInterface
{
    use HasRelationships;
    use HasNestedFieldSet;

    /**
     * AbstractRelationField constructor.
     */
    public function __construct(string $name, protected ?Closure $fieldSetCallback = null)
    {
        parent::__construct($name);
    }

    public function configureFieldSet(FieldSet $fieldSet)
    {
        if (! is_callable($this->getFieldSetCallback())) {
            return $fieldSet;
        }

        $result = $this->getFieldSetCallback()($fieldSet);

        if ($result instanceof FieldSet) {
            return $result;
        }

        return $fieldSet;
    }

    public function getFieldSetCallback()
    {
        return $this->fieldSetCallback;
    }

    public function setFieldSetCallback(callable $fieldSetCallback): self
    {
        $this->fieldSetCallback = $fieldSetCallback;

        return $this;
    }
}
