<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Button.
 */
class Button implements Renderable
{
    /**
     * @var Element
     */
    protected $element;

    /**
     * @var bool
     */
    protected $iconOnly;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string|null $name
     * @param null $value
     */
    public function __construct(string $name = null, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->element = Html::button();
        $this->element->addClass('button ');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @param $title
     * @return Button
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param $name
     * @return Button
     */
    public function withIcon($name)
    {
        $this->element->addClass('with-icon');
        $this->element->append(Html::i($name)->addClass('mt-icon'));

        return $this;
    }

    /**
     * @param $name
     * @return Button
     */
    public function withoutBackground()
    {
        $this->element->addClass('without-background');

        return $this;
    }

    /**
     * @param bool $cache
     *
     * @return $this
     */
    public function asAjaxbox($cache = false)
    {
        $this->element->addClass('ajaxbox');

        if ($cache) {
            $this->element->addAttributes(['data-cache' => 1]);
        }

        return $this;
    }

    /**
     * @param $inputType
     * @param string|null $visualType
     * @return Button
     */
    public function type($inputType, $visualType = null)
    {
        $attributes = ['type' => $inputType];
        $attributes += array_filter(['name' => $this->name, 'value' => $this->value]);

        $this->element->addAttributes($attributes);

        if ($visualType) {
            $this->element->addClass($visualType);
        }

        return $this;
    }

    /**
     * @return Button
     */
    public function iconOnly()
    {
        $this->iconOnly = true;

        return $this;
    }

    /**
     * @return Button
     */
    public function disableOnSubmit()
    {
        $this->element->addAttributes(['data-disable' => 'true']);

        return $this;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $this->element->addAttributes(['title' => $this->title]);

        if ($this->iconOnly) {
            $this->element->addClass('only-icon');
        } else {
            $this->element->append($this->title);
        }

        return $this->element;
    }

    /**
     * @param string|null $name
     * @param string|null $value
     * @return Button
     */
    public static function create(string $name = null, $value = null)
    {
        return new static($name, $value);
    }
}
