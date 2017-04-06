<?php

namespace CubeSystems\Leaf\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class StubRegistry
{
    /**
     * @var Collection
     */
    protected $stubs;

    public function __construct()
    {
        $this->stubs = new Collection();
    }

    /**
     * @param Filesystem $filesystem
     * @param string $stubDirectory
     * @return void
     */
    public function register( Filesystem $filesystem, string $stubDirectory )
    {
        $this->stubs = new Collection();

        $files = $filesystem->allFiles( $stubDirectory );

        foreach( $files as $file )
        {
            $this->stubs->push( new Stub( (string) $file ) );
        }
    }

    /**
     * @param string $name
     * @return Stub|null
     */
    public function findByName( string $name )
    {
        foreach( $this->getStubs() as $stub )
        {
            /** @var Stub $stub */
            if( $stub->getName() === $name )
            {
                return $stub;
            }
        }

        return null;
    }

    /**
     * @return Collection
     */
    public function getStubs(): Collection
    {
        return $this->stubs;
    }
}