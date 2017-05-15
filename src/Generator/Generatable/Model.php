<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\Schema;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Generator\Support\Traits\CompilesRelations;
use CubeSystems\Leaf\Services\FieldTypeRegistry;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class Model extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace, CompilesRelations;

    /**
     * @var FieldTypeRegistry
     */
    protected $fieldTypeRegistry;

    /**
     * @param StubRegistry $stubRegistry
     * @param Filesystem $filesystem
     * @param GeneratorFormatter $generatorFormatter
     * @param Schema $schema
     */
    public function __construct(
        StubRegistry $stubRegistry,
        Filesystem $filesystem,
        GeneratorFormatter $generatorFormatter,
        Schema $schema
    )
    {
        $this->fieldTypeRegistry = App::make( FieldTypeRegistry::class );

        parent::__construct( $stubRegistry, $filesystem, $generatorFormatter, $schema );
    }

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        return $this->stubRegistry->make( 'generator.model', [
            'namespace' => $this->getNamespace(),
            'use' => $this->getCompiledUseClasses(),
            'className' => $this->getClassName(),
            'traits' => $this->getCompiledTraits(),
            'tableName' => snake_case( $this->schema->getNamePlural() ),
            'fillable' => $this->getCompiledFillableFields(),
            'translatedAttributes' => $this->getCompiledTranslatedAttributes(),
            'toString' => $this->getCompiledToStringMethod(),
            'relations' => $this->getCompiledRelationMethods()
        ] );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->formatter->className( $this->schema->getNameSingular() );
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
    protected function getCompiledTraits(): string
    {
        $traits = new Collection();

        if ( $this->schema->hasTranslatables() )
        {
            $traits->push( 'Translatable' );
        }

        if ( $traits->isEmpty() )
        {
            return (string) null;
        }

        return 'use ' . $traits->implode( ', ' ) . ';' . PHP_EOL;
    }

    /**
     * @return string
     */
    protected function getCompiledFillableFields(): string
    {
        $fields = $this->schema->getNonTranslatableFields()->map( function( Field $field )
        {
            return '\'' . $this->formatter->field( $field->getName() ) . '\',';
        } );

        $fields = $fields->merge( $this->getFillableRelationFields() );

        return $this->formatter->indent( $fields->implode( PHP_EOL ), 2 );
    }

    /**
     * @return string
     */
    protected function getCompiledFillableTranslatables(): string
    {
        $fields = $this->schema->getTranslatableFields()->map( function( Field $field )
        {
            return '\'' . $this->formatter->field( $field->getName() ) . '\',';
        } );

        return $this->formatter->indent( $fields->implode( PHP_EOL ) );
    }

    /**
     * @return string
     */
    protected function getCompiledTranslatedAttributes(): string
    {
        if( !$this->selectGeneratables->contains( TranslationModel::class ) )
        {
            return (string) null;
        }

        $stub = $this->stubRegistry->make( 'generator.translated_attributes', [
            'fillableTranslatables' => $this->getCompiledFillableTranslatables()
        ] );

        return $this->formatter->indent( PHP_EOL . $stub );
    }

    /**
     * @return string
     */
    protected function getCompiledUseClasses(): string
    {
        $use = $this->getUseRelations();

        if( $this->schema->hasTranslatables() )
        {
            $use->push( $this->formatter->use( 'Dimsav\Translatable\Translatable' ) );
        }

        return $use->implode( PHP_EOL );
    }

    /**
     * @return string
     */
    protected function getCompiledToStringMethod(): string
    {
        $toStringField = $this->schema->getToStringField();
        $toStringField = $toStringField ? $toStringField->getName() : 'key';

        $stub = $this->stubRegistry->make( 'generator.method.to_string', [
            'fieldName' => Str::snake( $toStringField )
        ] );

        return $this->formatter->indent( PHP_EOL . $stub . PHP_EOL );
    }

    /**
     * @return string
     */
    protected function getCompiledRelationMethods(): string
    {
        if(
            !$this->selectGeneratables->contains( Model::class ) &&
            $this->selectGeneratables->contains( Page::class )
        )
        {
            return (string) null;
        }

        $fields = $this->compileRelationsMethods( $this->schema->getRelations() );

        return $this->formatter->indent( str_repeat( PHP_EOL, 2 ) . $fields->implode( str_repeat( PHP_EOL, 2 ) ) );
    }
}