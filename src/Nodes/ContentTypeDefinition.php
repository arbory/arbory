<?php

namespace Arbory\Base\Nodes;

use Closure;

class ContentTypeDefinition
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var \Closure
     */
    protected $fieldSetHandler;

    /**
     * @param string $model
     */
    public function __construct( string $model )
    {
        $this->model = $model;
        $this->name = $this->makeNameFromType( $model );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName( string $name )
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return Closure
     */
    public function getFieldSetHandler(): Closure
    {
        return $this->fieldSetHandler;
    }

    /**
     * @param Closure $fieldSetHandler
     */
    public function setFieldSetHandler( Closure $fieldSetHandler )
    {
        $this->fieldSetHandler = $fieldSetHandler;
    }

    /**
     * @param string $type
     * @return string
     */
    protected function makeNameFromType( $type ): string
    {
        return preg_replace( '/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', '$0', class_basename( $type ) );
    }
}
