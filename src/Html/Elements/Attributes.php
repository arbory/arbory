<?php

namespace Arbory\Base\Html\Elements;

use Illuminate\Support\Collection;

class Attributes extends Collection
{
    public function __toString()
    {
        return $this->attributesString();
    }

    protected function attributesString()
    {
        $html = [];

        foreach ($this->all() as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if (! is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? implode(' ', $html) : '';
    }

    protected function attributeElement($key, $value)
    {
        if (is_numeric($key)) {
            $key = $value;
        }

        if (! is_null($value)) {
            return $key.'="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8', true).'"';
        }
    }
}
