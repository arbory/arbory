<?php


namespace Arbory\Base\Admin\Form\Fields;


use Arbory\Base\Html\Elements\Element;

interface FieldRenderOptionsInterface
{
    /**
     * @param callable|null $value
     *
     * @return FieldRenderOptionsInterface
     */
    public function setWrapper(?callable $value):FieldRenderOptionsInterface;

    /**
     * @return callable|null
     */
    public function getWrapper():?callable;

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function addAttributes(array $attributes = []);

    /**
     * @return array
     */
    public function getAttributes():array;

    /**
     *
     * @param string|array $classes
     *
     * @return mixed
     */
    public function addClass($classes);

    /**
     * @return array
     */
    public function getClasses():array;
}