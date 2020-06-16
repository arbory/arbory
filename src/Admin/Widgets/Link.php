<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Link.
 */
class Link implements Renderable
{
    /**
     * @var Element
     */
    protected $element;

    /**
     * Link constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->element = Html::link()->addAttributes(['href' => $url]);
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
     * @return Link
     */
    public function title($title)
    {
        $this->element->append($title);
        $this->element->addAttributes(['title' => $title]);

        return $this;
    }

    /**
     * @param string|null $type
     * @return Link
     */
    public function asButton($type = null)
    {
        $this->element->addClass('button '.$type);

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
     * @param $name
     * @return Link
     */
    public function withIcon($name)
    {
        $this->element->addClass('with-icon');
        $this->element->append(Html::i($name)->addClass('mt-icon'));

        return $this;
    }

    /**
     * @return Element
     */
    public function render()
    {
        return $this->element;
    }

    /**
     * @param $url
     * @return Link
     */
    public static function create($url)
    {
        return new static($url);
    }
}
