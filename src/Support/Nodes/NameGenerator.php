<?php


namespace Arbory\Base\Support\Nodes;


class NameGenerator
{
    /**
     * @param  string  $type
     *
     * @return string
     */
    public function generate(string $type): string {
        $className = class_basename($type);
        $title = preg_replace('/Page$/', '', $className);

        return implode(
            ' ',
            preg_split(
                '/(?<=[a-z])(?=[A-Z])|(?=[A-Z][a-z])/',
                $title,
                -1,
                PREG_SPLIT_NO_EMPTY
            )
        );
    }
}