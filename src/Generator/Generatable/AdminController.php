<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Generator\Stubable;
use Illuminate\Console\DetectsApplicationNamespace;
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
    protected function registerAdminModel() {
        $className = $this->getNamespace() . $this->getClassName();

        $stub = $this->stubRegistry->make( 'register_admin_module', [
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
        $useFields = $this->formatter->useFields( clone $this->schema->getFields() );
        $useFields->push(
            $this->formatter->use(
                $this->getAppNamespace() .
                $this->formatter->className( $this->schema->getName() )
            )
        );

        $formFields = (clone $this->schema->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return sprintf(
                '$form->addField( new %s(\'%s\') );',
                $field->getClassName(),
                snake_case( $field->getName() )
            );
        } );

        $gridFields = (clone $this->schema->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return '$grid->column( \'' .  snake_case( $field->getName() ) . '\' );';
        } );

        return $this->stubRegistry->make( 'admin_controller', [
            'namespace' => $this->getNamespace(),
            'className' => $this->getClassName(),
            'resourceName' => $this->formatter->className( $this->schema->getName() ). '::class',
            'use' => $useFields->implode( PHP_EOL ),
            'formFields' => $this->formatter->indent( $formFields, 3 )->implode( PHP_EOL ),
            'gridFields' => $this->formatter->indent( $gridFields, 3 )->implode( PHP_EOL ),
        ] );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->formatter->className( $this->schema->getName() ) . 'Controller';
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
        return $this->getAppNamespace() . 'Http\Controllers\Admin\\';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return app_path( 'Http/Controllers/Admin/' . $this->getFilename() );
    }
}