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
    public function __toString()
    {
        return $this->tag->setContent($this->content)->__toString();
    }

    /**
     * @return Attributes
     */
    public function attributes()
    {
        return $this->tag->getAttributes();
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function addAttributes(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->attributes()->put($name, $value);
        }

        return $this;
    }

    /**
     * @return Content
     */
    public function content()
    {
        if ($this->content === null) {
            $this->content = new Content;
        }

        return $this->content;
    }

    /**
     * @param string $name
     * @param string|null $content
     * @return Tag
     */
    public function tag($name, $content = null)
    {
        return (new Tag($name))
            ->setAttributes($this->attributes())
            ->setContent($content);
    }

    /**
     * @param string $class
     * @return $this
     */
    public function addClass($class)
    {
        $currentClass = $this->attributes()->get('class');

        $this->attributes()->put('class', $currentClass ? implode(' ', [$currentClass, $class]) : $class);

        return $this;
    }

    /**
     * @param Element|array|string $content
     * @return $this
     */
    public function append($content)
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

    /**
     * @param Element|array|string $content
     * @return $this
     */
    public function prepend($content)
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
    public static function formatName($name): string
    {
        // Normalize multiple ending pattern
        $name = str_replace('[ ]', self::FIELD_NAME_MULTIPLE_ENDING, $name);

        $multiple = Str::endsWith($name, [self::FIELD_NAME_MULTIPLE_ENDING]);

        if ($multiple) {
            $name = Str::substr($name, 0, strlen(self::FIELD_NAME_MULTIPLE_ENDING) * -1);
        }

        $nameParts = preg_split('/\./', $name, null, PREG_SPLIT_NO_EMPTY);

        $inputName = Arr::pull($nameParts, 0);

        if (count($nameParts) > 0) {
            $inputName .= '['.implode('][', $nameParts).']';
        }

        return $inputName.($multiple ? self::FIELD_NAME_MULTIPLE_ENDING : '');
    }
}
