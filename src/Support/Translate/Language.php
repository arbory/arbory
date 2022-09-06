<?php

namespace Arbory\Base\Support\Translate;

class Language extends \Waavi\Translation\Models\Language
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
