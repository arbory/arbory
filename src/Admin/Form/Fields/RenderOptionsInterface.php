<?php


namespace Arbory\Base\Admin\Form\Fields;


use Arbory\Base\Html\Elements\Element;

interface RenderOptionsInterface
{
    /**
     * @param callable|null $value
     *
     * @return RenderOptionsInterface
     */
    public function setWrapper(?callable $value):RenderOptionsInterface;

    /**
     * @return callable|null
     */
    public function getWrapper():?callable;

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function addAttributes(array $attributes):RenderOptionsInterface;

    /**
     * @param array $attributes
     *
     * @return RenderOptionsInterface
     */
    public function setAttributes(array $attributes):RenderOptionsInterface;

    /**
     * @return array
     */
    public function getAttributes():array;

    /**
     * @param string|array $classes
     *
     * @return mixed
     */
    public function addClass($classes);

    /**
     * @param $classes
     *
     * @return RenderOptionsInterface
     */
    public function setClasses($classes):RenderOptionsInterface;

    /**
     * @return array
     */
    public function getClasses():array;
}