<?php

namespace CubeSystems\Leaf\Files;

/**
 * Class LeafImage
 * @package CubeSystems\Leaf\Files
 */
class LeafImage extends LeafFile
{
    /**
     * @return string
     */
    public function getTable()
    {
        return ( new parent )->getTable();
    }

    /**
     * @param null $parameters
     * @return string
     */
    public function getUrl( $parameters = null )
    {
        return \GlideImage::from( $this->getLocalName() )
            ->setSourceDisk( $this->disk )
            ->getImageUrl( $parameters );
    }
}
