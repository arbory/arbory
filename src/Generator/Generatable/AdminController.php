<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Admin\Form\Fields\Translatable;
use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Generator\Stubable;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdminController extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace;

    /**
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function generate()
    {
        parent::generate();

        $this->registerAdminModel();
    }

    /**
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function registerAdminModel()
    {
        $className = $this->getNamespace() . $this->getClassName();

        $stub = $this->stubRegistry->make( 'parts.register_admin_module', [
            'className' => $className
        ] );

        $path = base_path( 'routes/admin.php' );

        if( !Str::contains( $this->filesystem->get( $path ), $className ) )
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
        return $this->stubRegistry->make( 'admin_controller', [
            'namespace' => $this->getNamespace(),
            'className' => $this->getClassName(),
            'resourceName' => $this->formatter->className( $this->schema->getNameSingular() ) . '::class',
            'use' => $this->getCompiledUseFields(),
            'formFields' => $this->getCompiledFormFields(),
            'gridFields' => $this->getCompiledGridFields(),
        ] );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->formatter->className( $this->schema->getNameSingular() ) . 'Controller';
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
        return $this->getAppNamespace() . 'Http\Controllers\Admin\\';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return app_path( 'Http/Controllers/Admin/' . $this->getFilename() );
    }

    /**
     * @return string
     */
    protected function getCompiledFormFields(): string
    {
        $fields = $this->schema->getFields()->map( function( Field $field )
        {
            $compiled = sprintf(
                'new %s(\'%s\')',
                $field->getClassName(),
                snake_case( $field->getName() )
            );

            if( $field->getStructure()->isTranslatable() )
            {
                $compiled = sprintf(
                    'new Translatable( %s )',
                    $compiled
                );
            }

            return '$form->addField( ' . $compiled . ' );';
        } );

        return $this->formatter->indent( $fields->implode( PHP_EOL ), 3 );
    }

    /**
     * @return string
     */
    protected function getCompiledGridFields(): string
    {
        $fields = $this->schema->getFields()->map( function( Field $field )
        {
            return '$grid->column( \'' . snake_case( $field->getName() ) . '\' );';
        } );

        return $this->formatter->indent( $fields->implode( PHP_EOL ), 3 );
    }

    /**
     * @return string
     */
    protected function getCompiledUseFields(): string
    {
        $fields = $this->schema->getFields()->map( function( Field $field )
        {
            return $this->formatter->use( $field->getType() );
        } )->unique();

        $fields->push(
            $this->formatter->use(
                $this->getAppNamespace() .
                $this->formatter->className( $this->schema->getNameSingular() )
            )
        );

        if( $this->schema->hasTranslatables() )
        {
            $fields->push( $this->formatter->use( Translatable::class ) );
        }

        return $fields->implode( PHP_EOL );
    }
}