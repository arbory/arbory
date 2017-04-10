<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Generator\Stubable;
use Illuminate\Console\DetectsApplicationNamespace;

class AdminController extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace;

    public function generate()
    {
        parent::generate();

        $this->registerAdminRoute();
    }

    /**
     * @return void
     */
    protected function registerAdminRoute()
    {
        $path = base_path( 'routes/admin.php' );
        $route = 'AdminRoute::register( ' . $this->getNamespace() . $this->getClassName() . '::class );';

        if( !str_contains( $this->filesystem->get( $path ), $route ) )
        {
            $this->filesystem->append(
                $path,
                PHP_EOL . $route
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

        $replace = [
            '{{namespace}}' => $this->getNamespace(),
            '{{className}}' => $this->getClassName(),
            '{{resourceName}}' => $this->formatter->className( $this->schema->getName() ). '::class',
            '{{use}}' => $useFields->implode( PHP_EOL ),
            '{{formFields}}' => $this->formatter->prependSpacing( $formFields, 3 )->implode( PHP_EOL ),
            '{{gridFields}}' => $this->formatter->prependSpacing( $gridFields, 3 )->implode( PHP_EOL ),
        ];

        return str_replace(
            array_keys( $replace ),
            array_values( $replace ),
            $this->stubRegistry->findByName( 'admin_controller' )->getContents()
        );
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