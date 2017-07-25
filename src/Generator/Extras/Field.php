<?php

namespace Arbory\Base\Generator\Extras;

class Field
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var Structure
     */
    protected $structure;

    /**
     * @param Structure $structure
     */
    public function __construct( Structure $structure )
    {
        $this->structure = $structure;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDatabaseName(): string
    {
        return snake_case( $this->getName() );
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType( string $type )
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return class_basename( $this->getType() );
    }

    /**
     * @return Structure
     */
    public function getStructure(): Structure
    {
        return $this->structure;
    }

    /**
     * @param Structure $structure
     */
    public function setStructure( Structure $structure )
    {
        $this->structure = $structure;
    }
}
