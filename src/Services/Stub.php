<?php

namespace CubeSystems\Leaf\Services;

class Stub
{
    protected $path;

    public function __construct( $path )
    {
        $this->path = $path;
    }

    /**
     * @return bool|string
     */
    public function getContents()
    {
        return file_get_contents( $this->getPath() );
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return basename( $this->getPath(), '.stub' );
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
}