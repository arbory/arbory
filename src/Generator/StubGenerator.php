<?php

namespace CubeSystems\Leaf\Generator;

use CubeSystems\Leaf\Services\Stub;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

abstract class StubGenerator
{
    /**
     * @var Stub
     */
    protected $stub;

    /**
     * @var StubRegistry
     */
    protected $stubRegistry;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var GeneratorFormatter
     */
    protected $formatter;

    /**
     * @var Schema
     */
    protected $schema;

    /**
     * @var Collection
     */
    protected $selectGeneratables;

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
        $this->stubRegistry = $stubRegistry;
        $this->filesystem = $filesystem;
        $this->formatter = $generatorFormatter;
        $this->schema = $schema;
    }

    /**
     * @return void
     */
    public function generate()
    {
        $this->filesystem->put(
            $this->getPath(),
            $this->format( $this->getCompiledControllerStub() )
        );
    }

    /**
     * @return string
     */
    abstract public function getCompiledControllerStub(): string;

    /**
     * @return string
     */
    abstract public function getPath(): string;

    /**
     * @param Collection $selectGeneratables
     */
    public function setSelectGeneratables( Collection $selectGeneratables )
    {
        $this->selectGeneratables = $selectGeneratables;
    }

    /**
     * @param string $contents
     * @return string
     */
    protected function format( string $contents ): string
    {
        $contents = preg_replace( "/(\r?\n?\t){2,}/", "\n\n", $contents );

        return $contents;
    }
}