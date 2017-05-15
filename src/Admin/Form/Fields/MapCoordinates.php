<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Renderer\MapCoordinatesFieldRenderer;

// TODO; should make lat, lng, zoom defaults configurable
class MapCoordinates extends AbstractField
{
    /**
     * @var int
     */
    protected $zoom = 12;

    /**
     * @var float
     */
    protected $latitude = 56.94725473000847;

    /**
     * @var float
     */
    protected $longitude = 24.099142639160167;

    /**
     * @return string
     */
    public function render()
    {
        return ( new MapCoordinatesFieldRenderer( $this ) )->render();
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return [
            'data-zoom' => $this->zoom,
            'data-latitude' => $this->latitude,
            'data-longitude' => $this->longitude
        ];
    }

    /**
     * @param int $zoom
     * @return self
     */
    public function zoom( int $zoom )
    {
        $this->zoom = $zoom;

        return $this;
    }

    /**
     * @param float $latitude
     * @return self
     */
    public function latitude( float $latitude )
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @param float $longitude
     * @return self
     */
    public function longitude( float $longitude )
    {
        $this->longitude = $longitude;

        return $this;
    }
}