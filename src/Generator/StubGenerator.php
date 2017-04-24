<?php

namespace CubeSystems\Leaf\Generator;

use CubeSystems\Leaf\Services\Stub;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Filesystem\Filesystem;

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
     * @var MiscGenerators
     */
    protected $generators;

    /**
     * @var Schema
     */
    protected $schema;

    /**
     * @param StubRegistry $stubRegistry
     * @param Filesystem $filesystem
     * @param GeneratorFormatter $generatorFormatter
     * @param MiscGenerators $generators
     * @param Schema $schema
     */
    public function __construct(
        StubRegistry $stubRegistry,
        Filesystem $filesystem,
        GeneratorFormatter $generatorFormatter,
        MiscGenerators $generators,
        Schema $schema
    )
    {
        $this->stubRegistry = $stubRegistry;
        $this->filesystem = $filesystem;
        $this->formatter = $generatorFormatter;
        $this->generators = $generators;
        $this->schema = $schema;
    }

    /**
     * @return void
     */
    public function generate()
    {
        $this->filesystem->put(
            $this->getPath(),
            $this->getCompiledControllerStub()
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
}