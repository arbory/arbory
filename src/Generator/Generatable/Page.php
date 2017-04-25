<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Extras\Relation;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

class Page extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace;

    /**
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function generate()
    {
        parent::generate();

        $this->registerPage();
    }

    /**
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function registerPage()
    {
        $className = $this->getClassName();
        $pageClassName = $this->getNamespace() . '\\' . $className;

        $stub = $this->stubRegistry->make( 'parts.register_page', [
            'pageClassName' => $pageClassName,
            'controllerClassName' => sprintf(
                '%sHttp\Controllers\%s',
                $this->getAppNamespace(),
                $className . 'Controller'
            ),
        ] );

        $path = base_path( 'routes/web.php' );

        if( !Str::contains( $this->filesystem->get( $path ), $pageClassName ) )
        {
            $this->filesystem->append(
                $path,
                PHP_EOL . $stub
            );
        }
    }

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        return $this->stubRegistry->make( 'page', [
            'namespace' => $this->getNamespace(),
            'use' => $this->getCompiledUseClasses(),
            'className' => $this->getClassName(),
            'fillable' => $this->getCompiledFillableFields(),
            'fieldSet' => $this->getCompiledFieldSet(),
            'relations' => $this->getCompiledRelationMethods()
        ] );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->formatter->className( $this->schema->getName() ) . 'Page';
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
        return $this->getAppNamespace() . 'Pages';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return app_path( 'Pages/' . $this->getFilename() );
    }

    /**
     * @return string
     */
    protected function getCompiledUseClasses(): string
    {
        return
            $this->getUseFields()
                ->merge( $this->getUseRelations() )
                ->merge( $this->getUseRelationFields() )
                ->implode( PHP_EOL );
    }

    /**
     * @return Collection
     */
    protected function getUseFields(): Collection
    {
        return $this->schema->getFields()->map( function( Field $field )
        {
            return $this->formatter->use( $field->getType() );
        } )->unique();
    }

    /**
     * @return Collection
     */
    protected function getUseRelations(): Collection
    {
        return $this->schema->getRelations()->map( function( Relation $relation )
        {
            return $this->formatter->use( $relation->getModel() );
        } )->unique();
    }

    /**
     * @return Collection
     */
    protected function getUseRelationFields(): Collection
    {
        return $this->schema->getRelations()->map( function( Relation $relation )
        {
            return $this->formatter->use( $relation->getFieldType() );
        } )->unique();
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

    /**
     * @return string
     */
    protected function getCompiledRelationMethods(): string
    {
        $fields = $this->schema->getRelations()->map( function( Relation $relation )
        {
            $modelReflection = new ReflectionClass( $relation->getModel() );

            return $this->stubRegistry->make( 'parts.model_relation_method', [
                'methodName' => Str::camel( $modelReflection->getShortName() ),
                'relationMethod' => 'morphMany',
                'modelClass' => $modelReflection->getShortName(),
                'relationName' => 'owner'
            ] );
        } );

        return $this->formatter->indent( $fields->implode( PHP_EOL ) );
    }

    /**
     * @return string
     */
    protected function getCompiledFieldSet(): string
    {
        $fields = $this->getFieldFieldSet()->merge( $this->getRelationFieldSet() );

        return $this->formatter->indent( $fields->implode( PHP_EOL ), 2 );
    }

    /**
     * @return Collection
     */
    protected function getFieldFieldSet(): Collection
    {
        return $this->schema->getFields()->map( function( Field $field )
        {
            return $this->stubRegistry->make( 'parts.field', [
                'fieldClass' => $field->getClassName(),
                'fieldName' => $field->getName()
            ] );
        } );
    }

    /**
     * @return Collection
     */
    protected function getRelationFieldSet(): Collection
    {
        return $this->schema->getRelations()->map( function( Relation $relation )
        {
            $fieldReflection = new ReflectionClass( $relation->getFieldType() );
            $modelReflection = new ReflectionClass( $relation->getModel() );

            return $this->stubRegistry->make( 'parts.field_relation', [
                'relationFieldClass' => $fieldReflection->getShortName(),
                'relationName' => Str::camel( $modelReflection->getShortName() ),
                'fields' => ''
            ] );
        } );
    }
}