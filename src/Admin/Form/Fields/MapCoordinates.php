<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Renderer\MapCoordinatesFieldRenderer;

class MapCoordinates extends AbstractField
{
    /**
     * @var int
     */
    protected $zoom;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * @return string
     */
    public function render()
    {
        $this->zoom = config( 'leaf.fields.map_coordinates.zoom' );
        $this->latitude = config( 'leaf.fields.map_coordinates.coordinates.lat' );
        $this->longitude = config( 'leaf.fields.map_coordinates.coordinates.lng' );

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