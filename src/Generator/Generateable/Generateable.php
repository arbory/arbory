<?php

namespace CubeSystems\Leaf\Generator\Generateable;

interface Generateable
{
    public function generate();

    public function getCompiledControllerStub(): string;

    public function getClassName(): string;

    public function getFilename(): string;

    public function getNamespace(): string;
}