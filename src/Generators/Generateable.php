<?php

namespace CubeSystems\Leaf\Generators;

interface Generateable
{
    public function generate();

    public function getCompiledControllerStub(): string;

    public function getClassName(): string;

    public function getFilename(): string;

    public function getNamespace(): string;
}