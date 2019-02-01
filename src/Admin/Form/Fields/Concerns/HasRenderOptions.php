<?php


namespace Arbory\Base\Admin\Form\Fields\Concerns;


use Arbory\Base\Admin\Form\Fields\FieldRenderOptionsInterface;

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
    public function addAttributes( array $attributes = [] )
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     *
     * @param string|array $classes
     *
     * @return mixed
     */
    public function addClass( $classes )
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
     * @return FieldRenderOptionsInterface
     */
    public function setWrapper( ?callable $value ): FieldRenderOptionsInterface
    {
        $this->wrapper = $value;

        return $this;
    }
}