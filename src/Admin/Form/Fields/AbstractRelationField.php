<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

abstract class AbstractRelationField extends AbstractField
{
    protected $fieldSetCallback;

    /**
     * AbstractRelationField constructor.
     * @param string $name
     * @param callable $fieldSetCallback
     */
    public function __construct( $name, callable $fieldSetCallback )
    {
        parent::__construct( $name );

        $this->fieldSetCallback = $fieldSetCallback;
    }

    /**
     * @return Relation
     */
    protected function getRelation()
    {
        return $this->getModel()->{$this->getName()}();
    }

    /**
     * @return Model
     */
    protected function getRelatedModel()
    {
        return $this->getRelation()->getRelated();
    }
}
