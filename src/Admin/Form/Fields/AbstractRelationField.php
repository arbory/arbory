<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Concerns\HasNestedFieldSet;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRelationships;
use Arbory\Base\Admin\Form\FieldSet;

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
    public function __construct( $name, callable $fieldSetCallback = null )
    {
        parent::__construct( $name );

        $this->fieldSetCallback = $fieldSetCallback;
    }
    
    public function configureFieldSet( FieldSet $fieldSet )
    {
        if(false === is_callable($this->getFieldSetCallback())) {
            return $fieldSet;
        }

        $result = $this->getFieldSetCallback()($fieldSet);

        if($result instanceof FieldSet) {
            return $result;
        } else {
            return $fieldSet;
        }
    }

    protected function getFieldSetCallback()
    {
        return $this->fieldSetCallback;
    }
}
