<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options;

use Illuminate\Support\Fluent;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;

class StyleOptions implements StyleOptionsInterface
{
    use HasRenderOptions;

    /**
     * @var Fluent
     */
    protected $additional;

    /**
     * @var mixed
     */
    protected $rows;

    public function __construct()
    {
        $this->additional = new Fluent();
    }

    /**
     * @param array $data
     *
     * @return StyleOptionsInterface
     */
    public function setAdditional(array $data): StyleOptionsInterface
    {
        $this->additional = new Fluent($data);

        return $this;
    }

    /**
     * @param array $data
     *
     * @return StyleOptionsInterface
     */
    public function addAdditional(array $data): StyleOptionsInterface
    {
        $this->additional = new Fluent(
            array_merge($this->additional->getAttributes(), $data)
        );

        return $this;
    }

    /**
     * @return Fluent
     */
    public function getAdditional(): Fluent
    {
        return $this->additional;
    }

    /**
     * @return mixed
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param int $rows
     * @param array $breakpoints
     *
     * @return StyleOptionsInterface
     */
    public function setRows(int $rows, array $breakpoints = []): StyleOptionsInterface
    {
        $this->rows = ['size' => $rows, 'breakpoints' => $breakpoints];

        return $this;
    }
}
