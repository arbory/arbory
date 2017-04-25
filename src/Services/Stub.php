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
        $path = $this->getPath();
        $relative = substr( $path, strpos( $path, 'stubs' ) + 6 );

        return str_replace(
            [ DIRECTORY_SEPARATOR, '.stub' ],
            [ '.', '' ],
            $relative
        );
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
}