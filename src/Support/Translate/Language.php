<?php

namespace CubeSystems\Leaf\Support\Translate;

class Language extends \Waavi\Translation\Models\Language
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }
}