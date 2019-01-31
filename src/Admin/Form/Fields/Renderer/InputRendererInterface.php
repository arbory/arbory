<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer;


interface InputRendererInterface
{
    /**
     * @param array $attributes
     *
     * @return InputRendererInterface
     */
    public function setAttributes($attributes = []): InputRendererInterface;

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param array $attributes
     *
     * @return InputRendererInterface
     */
    public function addAttributes($attributes = []): InputRendererInterface;

    /**
     * @return mixed
     */
    public function __toString();
}