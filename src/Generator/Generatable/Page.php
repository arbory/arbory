<?php

namespace Arbory\Base\Generator\Generatable;

use Arbory\Base\Admin\Form\Fields\HasMany;
use Arbory\Base\Admin\Form\Fields\HasOne;
use Arbory\Base\Generator\Extras\Field;
use Arbory\Base\Generator\Extras\Relation;
use Arbory\Base\Generator\Stubable;
use Arbory\Base\Generator\StubGenerator;
use Arbory\Base\Generator\Support\Traits\CompilesRelations;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Page extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace, CompilesRelations;

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

        $stub = $this->stubRegistry->make( 'generator.support.register_page', [
            'pageClassName' => $pageClassName,
            'fieldSet' => $this->getCompiledFieldSet(),
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
                str_repeat( PHP_EOL, 2 ) . $stub
            );
        }
    }

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        return $this->stubRegistry->make( 'generator.page', [
            'namespace' => $this->getNamespace(),
            'use' => $this->getCompiledUseClasses(),
            'className' => $this->getClassName(),
            'fillable' => $this->getCompiledFillableFields(),
            'relations' => $this->getCompiledRelationMethods()
        ] );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->formatter->className( $this->schema->getNameSingular() ) . 'Page';
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
        if( !$this->selectGeneratables->contains( Page::class ) )
        {
            return (string) null;
        }

        $fields = $this->compileRelationsMethods( $this->schema->getRelations() );

        return $this->formatter->indent( $fields->implode( str_repeat( PHP_EOL, 2 ) ) );
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
            return $this->stubRegistry->make( 'generator.method.part.field_set_add', [
                'fieldClass' => $field->getType(),
                'fieldName' => Str::snake( $field->getName() )
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
            $modelName = class_basename( $relation->getModel() );

            return $this->stubRegistry->make( 'parts.field_relation', [
                'relationFieldClass' => $relation->getFieldType(),
                'relationName' => Str::camel( $modelName ),
                'fields' => ''
            ] );
        } );
    }
}
