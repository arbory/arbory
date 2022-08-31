<?php

namespace Arbory\Base\Html\Elements;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class Element.
 */
class Element
{
    private const FIELD_NAME_MULTIPLE_ENDING = '[]';

    /**
     * @var Tag
     */
    protected $tag;

    /**
     * @var Content
     */
    protected $content;

    /**
     * Element constructor.
     *
     * @param $tag
     * @param null $content
     */
    public function __construct($tag, $content = null)
    {
        $this->tag = new Tag($tag);

        if ($content !== null) {
            $this->append($content);
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->tag->setContent($this->content)->__toString();
    }

    /**
     * @return Attributes
     */
    public function attributes(): Attributes
    {
        return $this->tag->getAttributes();
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function addAttributes(array $attributes): self
    {
        foreach ($attributes as $name => $value) {
            $this->attributes()->put($name, $value);
        }

        return $this;
    }

    /**
     * @return Content
     */
    public function content(): Content
    {
        if ($this->content === null) {
            $this->content = new Content;
        }

        return $this->content;
    }

    public function tag(string $name, string $content = null): Tag
    {
        return (new Tag($name))
            ->setAttributes($this->attributes())
            ->setContent($content);
    }

    public function addClass(?string $class): self
    {
        $currentClass = $this->attributes()->get('class');

        $this->attributes()->put('class', $currentClass ? implode(' ', [$currentClass, $class]) : $class);

        return $this;
    }

    public function append(mixed $content): self
    {
        if (is_array($content)) {
            foreach ($content as $item) {
                $this->append($item);
            }

            return $this;
        }

        if (is_string($content)) {
            $content = e($content);
        }

        $this->content()->push($content);

        return $this;
    }

    public function prepend(mixed $content): self
    {
        if (is_array($content)) {
            foreach ($content as $item) {
                $this->prepend($item);
            }

            return $this;
        }

        $this->content()->prepend($content);

        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    public static function formatName(string $name): string
    {
        // Normalize multiple ending pattern
        $name = str_replace('[ ]', self::FIELD_NAME_MULTIPLE_ENDING, $name);

        $multiple = Str::endsWith($name, [self::FIELD_NAME_MULTIPLE_ENDING]);

        if ($multiple) {
            $name = Str::substr($name, 0, strlen(self::FIELD_NAME_MULTIPLE_ENDING) * -1);
        }

        $nameParts = preg_split('/\./', $name, -1, PREG_SPLIT_NO_EMPTY);

        $inputName = Arr::pull($nameParts, 0);

        if (count($nameParts) > 0) {
            $inputName .= '[' . implode('][', $nameParts) . ']';
        }

        return $inputName . ($multiple ? self::FIELD_NAME_MULTIPLE_ENDING : '');
    }
}
