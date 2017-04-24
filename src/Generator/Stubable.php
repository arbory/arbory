<?php

namespace CubeSystems\Leaf\Generator;

interface Stubable
{
    public function getCompiledControllerStub(): string;

    public function getClassName(): string;

    public function getFilename(): string;

    public function getNamespace(): string;

    public function getPath(): string;
}