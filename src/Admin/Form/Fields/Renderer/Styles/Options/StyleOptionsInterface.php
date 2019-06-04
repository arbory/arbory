<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options;

use Illuminate\Support\Fluent;
use Arbory\Base\Admin\Form\Fields\RenderOptionsInterface;

interface StyleOptionsInterface extends RenderOptionsInterface
{
    /**
     * @param array $data
     *
     * @return StyleOptionsInterface
     */
    public function setAdditional(array $data): self;

    /**
     * @param array $data
     *
     * @return StyleOptionsInterface
     */
    public function addAdditional(array $data): self;

    /**
     * @return Fluent
     */
    public function getAdditional(): Fluent;

    /**
     * @param mixed $rows
     * @param array $breakpoints
     *
     * @return StyleOptionsInterface
     */
    public function setRows(int $rows, array $breakpoints = []): self;

    /**
     * @return mixed
     */
    public function getRows();
}
