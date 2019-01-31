<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\MapCoordinatesFieldRenderer;

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

    protected $renderer = MapCoordinatesFieldRenderer::class;

    protected $style = 'nested';

    public function __construct( string $name )
    {
        parent::__construct($name);

        $this->zoom = config( 'arbory.fields.map_coordinates.zoom' );
        $this->latitude = config( 'arbory.fields.map_coordinates.coordinates.lat' );
        $this->longitude = config( 'arbory.fields.map_coordinates.coordinates.lng' );

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
