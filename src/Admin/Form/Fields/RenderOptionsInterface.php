<?php

namespace Arbory\Base\Admin\Form\Fields;

interface RenderOptionsInterface
{
    /**
     * @param callable|null $value
     * @return RenderOptionsInterface
     */
    public function setWrapper(?callable $value): self;

    /**
     * @return callable|null
     */
    public function getWrapper(): ?callable;

    /**
     * @param array $attributes
     * @return mixed
     */
    public function addAttributes(array $attributes): self;

    /**
     * @param array $attributes
     * @return RenderOptionsInterface
     */
    public function setAttributes(array $attributes): self;

    /**
     * @param array $attributes
     * @return RenderOptionsInterface
     */
    public function removeAttributes(array $attributes): self;

    /**
     * @return array
     */
    public function getAttributes(): array;

    /**
     * @return mixed
     */
    public function addClass(string|array $classes);

    /**
     * @param array|string $classes
     * @return RenderOptionsInterface
     */
    public function setClasses(array|string $classes): self;

    /**
     * @param array|string $classes
     * @return RenderOptionsInterface
     */
    public function removeClasses(array|string $classes): self;

    /**
     * @return array
     */
    public function getClasses(): array;
}
