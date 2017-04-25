<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\MiscGenerators;
use CubeSystems\Leaf\Generator\Schema;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Services\FieldTypeRegistry;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class Model extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace;

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
        return $this->stubRegistry->make( 'model', [
            'namespace' => $this->getNamespace(),
            'className' => $this->getClassName(),
            '$tableName' => snake_case( $this->schema->getName() ),
            'fillable' => $this->getCompiledFillableFields(),
            'properties' => $this->getCompiledProperties(),
        ] );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->formatter->className( $this->schema->getName() );
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
    protected function getCompiledProperties(): string
    {
        $stub = $this->stubRegistry->findByName( 'property' )->getContents();

        return $this->schema->getFields()->map( function( Field $field ) use ( $stub )
        {
            $replace = [
                '{{docVar}}' => $this->fieldTypeRegistry->getFieldTypeHint( $field->getType() ),
                '{{propertyScope}}' => 'protected',
                '{{propertyName}}' => $this->formatter->property( $field->getName() ),
            ];

            $stub = str_replace(
                array_keys( $replace ),
                array_values( $replace ),
                $stub
            );

            return $stub . PHP_EOL;
        } )->implode( PHP_EOL );
    }

    /**
     * @return string
     */
    protected function getCompiledFillableFields(): string
    {
        $fields = $this->schema->getFields()->map( function( Field $field )
        {
            return '\'' . $this->formatter->field( $field->getName() ) . '\',';
        } );

        return $this->formatter->indent( $fields->implode( PHP_EOL ), 2 );
    }
}