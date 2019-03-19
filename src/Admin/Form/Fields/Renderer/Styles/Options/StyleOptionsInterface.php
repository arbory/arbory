<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options;


use Arbory\Base\Admin\Form\Fields\RenderOptionsInterface;
use Illuminate\Support\Fluent;

interface StyleOptionsInterface extends RenderOptionsInterface
{
    /**
     * @param array $data
     *
     * @return StyleOptionsInterface
     */
    public function setAdditional( array $data ):StyleOptionsInterface;

    /**
     * @param array $data
     *
     * @return StyleOptionsInterface
     */
    public function addAdditional( array $data ):StyleOptionsInterface;

    /**
     * @return Fluent
     */
    public function getAdditional():Fluent;

    /**
     * @param mixed $rows
     * @param array $breakpoints
     *
     * @return StyleOptionsInterface
     */
    public function setRows( int $rows, array $breakpoints = [] ):StyleOptionsInterface;

    /**
     * @return mixed
     */
    public function getRows();
}