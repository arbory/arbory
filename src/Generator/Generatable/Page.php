<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Str;

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
    protected function registerPage() {
        $className = $this->getClassName();
        $pageClassName = $this->getNamespace() . '\\' . $className;

        $stub = $this->stubRegistry->make( 'register_page', [
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
        $fieldSet = ( clone $this->schema->getFields() )->transform( function( $field )
        {
            /**
             * @var Field $field
             */
            // todo: sprintf
            return '$fieldSet->add( new ' . $field->getClassName() . '( \'' . $field->getName() . '\' ) );';
        } );

        return $this->stubRegistry->make( 'page', [
            'namespace' => $this->getNamespace(),
            'use' => $this->formatter->useFields( clone $this->schema->getFields() )->implode( PHP_EOL ),
            'className' => $this->getClassName(),
            'fieldSet' => $this->formatter->prependSpacing( $fieldSet, 2 )->implode( PHP_EOL ),
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
}