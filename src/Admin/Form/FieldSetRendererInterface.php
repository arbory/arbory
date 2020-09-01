<?php

namespace Arbory\Base\Admin\Form;

use Illuminate\Contracts\Support\Renderable;

interface FieldSetRendererInterface extends Renderable
{
    /**
     * @return string|null
     */
    public function getDefaultStyle(): ?string;

    /**
     * @param string $value
     *
     * @return FieldSetRendererInterface
     */
    public function setDefaultStyle(string $value): self;
}
