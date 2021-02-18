<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;
use Arbory\Base\Admin\Form\Fields\Concerns\HasNestedFieldSet;
use Arbory\Base\Admin\Form\Fields\Renderer\MapCoordinatesFieldRenderer;

class MapCoordinates extends AbstractField implements NestedFieldInterface, RenderOptionsInterface
{
    use HasNestedFieldSet;
    use HasRenderOptions;

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

    protected $rendererClass = MapCoordinatesFieldRenderer::class;

    protected $style = 'nested';

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->zoom = config('arbory.fields.map_coordinates.zoom');
        $this->latitude = config('arbory.fields.map_coordinates.coordinates.lat');
        $this->longitude = config('arbory.fields.map_coordinates.coordinates.lng');
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return [
            'data-zoom' => $this->zoom,
            'data-latitude' => $this->latitude,
            'data-longitude' => $this->longitude,
        ];
    }

    /**
     * @param int $zoom
     * @return self
     */
    public function zoom(int $zoom)
    {
        $this->zoom = $zoom;

        return $this;
    }

    /**
     * @param float $latitude
     * @return self
     */
    public function latitude(float $latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @param float $longitude
     * @return self
     */
    public function longitude(float $longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function configureFieldSet(FieldSet $fieldSet)
    {
        $namespace = $fieldSet->getNamespace();

        $namespace = substr($namespace, 0, strrpos($namespace, '.'));
        $fieldSet = new FieldSet($fieldSet->getModel(), $namespace);

        $fieldSet->hidden($this->getName())
            ->addAttributes($this->getData())
            ->addClass('coordinates-input')
            ->setDisabled($this->isDisabled())
            ->setInteractive($this->isInteractive());
        $fieldSet->text('search')
            ->setName('')
            ->setLabel('')
            ->addClass('search-input')
            ->setDisabled($this->isDisabled())
            ->setInteractive($this->isInteractive());

        return $fieldSet;
    }
}
