<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Html\Elements\Content;
use Illuminate\Contracts\Support\Renderable;

class Wrappable
{
    protected $wrapper;

    protected $prepended;
    protected $appended;

    public function __construct()
    {
        $this->prepended = new Content();
        $this->appended = new Content();

        $this->wrapper = function($content) {
            return $content;
        };
    }

    public function wrap(callable $wrapper)
    {
        $call = $this->wrapper;

        $this->wrapper = function ($content) use($call, $wrapper) {
            return $call($wrapper($content));
        };
    }

    public function append($content)
    {
        $this->appended->push($content);
    }

    public function prepend($content)
    {
        $this->prepended->push($content);
    }

    public function render($content)
    {
        $call = $this->wrapper;

        return $this->prepended->render() .
            $call($content) .
            $this->appended->render();
    }
}