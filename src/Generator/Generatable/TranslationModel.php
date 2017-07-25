<?php

namespace Arbory\Base\Generator\Generatable;

use Arbory\Base\Generator\Extras\Field;
use Arbory\Base\Generator\Stubable;
use Arbory\Base\Generator\StubGenerator;
use Illuminate\Console\DetectsApplicationNamespace;

class TranslationModel extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace;

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        return $this->stubRegistry->make( 'generator.model', [
            'namespace' => $this->getNamespace(),
            'use' => '',
            'className' => $this->getClassName(),
            'traits' => '',
            'tableName' => snake_case( $this->schema->getNameSingular() ) . '_translations',
            'fillable' => $this->getCompiledFillableFields(),
            'translatedAttributes' => '',
            'relations' => '',
            'toString' => ''
        ] );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->formatter->className( $this->schema->getNameSingular() . 'Translation' );
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->getClassName() . '.php';
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return rtrim( $this->getAppNamespace(), '\\' );
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return app_path( $this->getFilename() );
    }

    /**
     * @return string
     */
    protected function getCompiledFillableFields(): string
    {
        $fields = $this->schema->getTranslatableFields()->map( function( Field $field )
        {
            return '\'' . $this->formatter->field( $field->getName() ) . '\',';
        } );

        return $this->formatter->indent( $fields->implode( PHP_EOL ), 2 );
    }
}
