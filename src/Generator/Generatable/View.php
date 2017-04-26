<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;

class View extends StubGenerator implements Stubable
{
    /**
     * @return void
     */
    public function generate()
    {
        $path = $this->getPath();
        $directory = dirname( $path );

        if( !$this->filesystem->isDirectory( $directory ) )
        {
            $this->filesystem->makeDirectory( $directory );
        }

        $this->filesystem->put(
            $path,
            $this->getCompiledControllerStub()
        );
    }

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        return $this->stubRegistry->findByName( 'view' )->getContents();
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return (string) null;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return 'index.blade.php';
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return (string) null;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return base_path( sprintf(
            'resources/views/public/controllers/%s/%s',
            snake_case( $this->schema->getNameSingular() ),
            $this->getFilename()
        ) );
    }
}