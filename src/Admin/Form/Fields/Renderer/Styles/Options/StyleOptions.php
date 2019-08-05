<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options;


use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;
use Illuminate\Support\Fluent;

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
    public function setAdditional( array $data ): StyleOptionsInterface
    {
        $this->additional = new Fluent($data);

        return $this;
    }

    /**
     * @param array $data
     *
     * @return StyleOptionsInterface
     */
    public function addAdditional( array $data ): StyleOptionsInterface
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
     * @param mixed $rows
     *
     * @return StyleOptions
     */
    public function setRows( $rows ): StyleOptionsInterface
    {
        $this->rows = $rows;

        return $this;
    }
}