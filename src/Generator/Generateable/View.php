<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\Schema;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Services\Stub;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Filesystem\Filesystem;

class View extends StubGenerator implements Stubable
{
    use GeneratorFormatter;

    /**
     * @var Stub
     */
    protected $stub;

    /**
     * @var Schema
     */
    protected $schema;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param StubRegistry $stubRegistry
     * @param Filesystem $filesystem
     * @param Schema $schema
     */
    public function __construct(
        StubRegistry $stubRegistry,
        Filesystem $filesystem,
        Schema $schema
    )
    {
        $this->stub = $stubRegistry->findByName( 'view' );
        $this->filesystem = $filesystem;
        $this->schema = $schema;
    }

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
        return $this->stub->getContents();
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
            'resources/views/controllers/%s/%s',
            snake_case( $this->schema->getName() ),
            $this->getFilename()
        ) );
    }
}