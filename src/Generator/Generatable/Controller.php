<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use Illuminate\Console\DetectsApplicationNamespace;

class Controller extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace;

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        $viewFields = (clone $this->schema->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return sprintf(
                '\'%s\' => $node->%s,',
                snake_case( $field->getName() ),
                $this->formatter->property( $field->getName() )
            );
        } );

        return $this->stubRegistry->make( 'controller', [
            'namespace' => $this->getNamespace(),
            'className' => $this->getClassName(),
            'viewPath' => 'controllers.' . snake_case( $this->schema->getName() ) . '.index',
            'viewFields' => $this->formatter->indent( $viewFields, 3 )->implode( PHP_EOL ),
        ] );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->formatter->className( $this->schema->getName() ) . 'PageController';
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->getClassName() .'.php';
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->getAppNamespace() . 'Http\Controllers';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return app_path( 'Http/Controllers/' . $this->getFilename() );
    }
}