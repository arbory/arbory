<?php

namespace CubeSystems\Leaf\Generator;

use CubeSystems\Leaf\Services\Stub;
use Illuminate\Filesystem\Filesystem;

abstract class StubGenerator
{
    /**
     * @var Stub
     */
    protected $stub;

    /**
     * @var Filesystem
     */
    protected $filesystem;

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