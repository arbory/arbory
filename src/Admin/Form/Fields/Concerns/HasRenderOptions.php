<?php


namespace Arbory\Base\Admin\Form\Fields\Concerns;


use Arbory\Base\Admin\Form\Fields\RenderOptionsInterface;

trait HasRenderOptions
{

    /**
     * @var callable|null
     */
    protected $wrapper;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $classes = [];

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function addAttributes( array $attributes ):RenderOptionsInterface
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return RenderOptionsInterface
     */
    public function setAttributes( array $attributes ):RenderOptionsInterface
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     *
     * @param string|array $classes
     *
     * @return mixed
     */
    public function addClass( $classes ):RenderOptionsInterface
    {
        $classes = explode(" ", $classes);

        $this->classes = array_unique(
            array_merge(
                $this->classes,
                $classes
            )
        );

        return $this;
    }

    /**
     * @param $classes
     *
     * @return RenderOptionsInterface
     */
    public function setClasses( $classes ):RenderOptionsInterface
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * @return callable|null
     */
    public function getWrapper():?callable
    {
        return $this->wrapper;
    }

    /**
     * @param callable|null $value
     *
     * @return RenderOptionsInterface
     */
    public function setWrapper( ?callable $value ): RenderOptionsInterface
    {
        $this->wrapper = $value;

        return $this;
    }
}