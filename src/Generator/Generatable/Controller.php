<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Controller extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace;

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        return $this->stubRegistry->make( 'controller', [
            'namespace' => $this->getNamespace(),
            'className' => $this->getClassName(),
            'viewPath' => 'public.controllers.' . snake_case( $this->schema->getNameSingular() ) . '.index',
            'viewFields' => $this->getCompiledViewFields(),
        ] );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->formatter->className( $this->schema->getNameSingular() ) . 'PageController';
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

    /**
     * @return string
     */
    protected function getCompiledViewFields(): string
    {
        $fields = $this->schema->getFields()->map( function( Field $field ) {
            return sprintf(
                '\'%s\' => $node->content->%s,',
                $this->formatter->property( $field->getName() ),
                Str::snake( $field->getName() )
            );
        } );

        return $this->formatter->indent( $fields->implode( PHP_EOL ), 3 );
    }
}