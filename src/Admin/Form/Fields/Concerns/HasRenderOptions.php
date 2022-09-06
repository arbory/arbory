<?php

namespace Arbory\Base\Admin\Form\Fields\Concerns;

use Arbory\Base\Admin\Form\Fields\RenderOptionsInterface;
use Illuminate\Support\Arr;

trait HasRenderOptions
{
    /**
     * @var callable|null
     */
    protected $wrapper;

    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * @var array
     */
    protected array $classes = [];

    /**
     * @return mixed
     */
    public function addAttributes(array $attributes): RenderOptionsInterface
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    public function setAttributes(array $attributes): RenderOptionsInterface
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function removeAttributes(array $attributeKeys): RenderOptionsInterface
    {
        foreach ($attributeKeys as $key) {
            unset($this->attributes[$key]);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function addClass(string|array $classes): RenderOptionsInterface
    {
        $classes = explode(' ', $classes);

        $this->classes = array_unique(
            array_merge(
                $this->classes,
                $classes
            )
        );

        return $this;
    }

    public function setClasses(array|string $classes): RenderOptionsInterface
    {
        $this->classes = Arr::wrap($classes);

        return $this;
    }

    public function removeClasses(array|string $classes): RenderOptionsInterface
    {
        $classes = Arr::wrap($classes);

        $this->classes = array_filter($this->classes, fn ($value) => ! in_array($value, $classes, true));

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getClasses(): array
    {
        return $this->classes;
    }

    public function getWrapper(): ?callable
    {
        return $this->wrapper;
    }

    public function setWrapper(?callable $value): RenderOptionsInterface
    {
        $this->wrapper = $value;

        return $this;
    }
}
