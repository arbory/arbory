<?php

namespace Arbory\Base\Files;

/**
 * Class ArboryImage.
 */
class ArboryImage extends ArboryFile
{
    /**
     * @return string
     */
    public function getTable()
    {
        return (new parent)->getTable();
    }

    /**
     * @param null $parameters
     * @return string
     */
    public function getUrl($parameters = null)
    {
        try {
            return \GlideImage::from($this->getLocalName())
                ->setSourceDisk($this->disk)
                ->getImageUrl($parameters);
        } catch (\Exception $e) {
            \Log::warning($e);
        }
    }
}
