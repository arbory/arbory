<?php

namespace Arbory\Base\Admin\Form\Fields;

interface RenderOptionsInterface
{
    /**
     * @param callable|null $value
     *
     * @return RenderOptionsInterface
     */
    public function setWrapper(?callable $value): self;

    /**
     * @return callable|null
     */
    public function getWrapper(): ?callable;

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function addAttributes(array $attributes): self;

    /**
     * @param array $attributes
     *
     * @return RenderOptionsInterface
     */
    public function setAttributes(array $attributes): self;

    /**
     * @param array $attributes
     *
     * @return RenderOptionsInterface
     */
    public function removeAttributes(array $attributes): self;

    /**
     * @return array
     */
    public function getAttributes(): array;

    /**
     * @param string|array $classes
     *
     * @return mixed
     */
    public function addClass($classes);

    /**
     * @param string|array $classes
     *
     * @return RenderOptionsInterface
     */
    public function setClasses($classes): self;

    /**
     * @param string|array $classes
     *
     * @return RenderOptionsInterface
     */
    public function removeClasses($classes): self;

    /**
     * @return array
     */
    public function getClasses(): array;
}
