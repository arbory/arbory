<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRelationships;
use Arbory\Base\Admin\Form\Fields\Concerns\HasNestedFieldSet;

abstract class AbstractRelationField extends AbstractField implements NestedFieldInterface
{
    use HasRelationships;
    use HasNestedFieldSet;

    protected $fieldSetCallback;

    /**
     * AbstractRelationField constructor.
     * @param string $name
     * @param callable $fieldSetCallback
     */
    public function __construct($name, callable $fieldSetCallback = null)
    {
        parent::__construct($name);

        $this->fieldSetCallback = $fieldSetCallback;
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

    /**
     * @param callable $fieldSetCallback
     *
     * @return AbstractRelationField
     */
    public function setFieldSetCallback(callable $fieldSetCallback): self
    {
        $this->fieldSetCallback = $fieldSetCallback;

        return $this;
    }
}
