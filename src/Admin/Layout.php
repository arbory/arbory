<?php

namespace Arbory\Base\Admin;

use Closure;
use Arbory\Base\Admin\Layout\Row;
use Illuminate\Support\Collection;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Admin\Layout\AbstractLayout;
use Illuminate\Contracts\Support\Renderable;
use Arbory\Base\Admin\Layout\LayoutInterface;
use Arbory\Base\Admin\Layout\Transformers\AppendTransformer;

/**
 * Class Layout.
 */
class Layout extends AbstractLayout implements Renderable, LayoutInterface
{
    /**
     * @var Collection|Row[]
     */
    protected $rows;

    protected $bodyClass;

    /**
     * Layout constructor.
     *
     * @param Closure|null $callback
     */
    public function __construct(Closure $callback = null)
    {
        $this->rows = new Collection();

        if ($callback instanceof Closure) {
            $callback($this);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @param mixed $content
     *
     * @return Layout
     */
    public function body($content)
    {
        return $this->row($content);
    }

    /**
     * @param $content
     *
     * @return $this
     */
    public function row($content)
    {
        $this->rows->push($this->createRow($content));

        return $this;
    }

    /**
     * @param $content
     *
     * @return Row
     */
    protected function createRow($content)
    {
        if ($content instanceof Closure) {
            $row = new Row();
            $content($row);

            return $row;
        }

        return new Row($content);
    }

    /**
     * @param $class
     *
     * @return Layout
     */
    public function bodyClass($class)
    {
        $this->bodyClass = $class;

        return $this;
    }

    public function build()
    {
        $this->use(new AppendTransformer(new Content($this->rows->all())));
    }

    /**
     * @param                      $content
     *
     * @return string
     * @throws \Throwable
     */
    public function contents($content)
    {
        $variables = [
            'content' => $content,
            'bodyClass' => $this->bodyClass,
        ];

        return view('arbory::controllers.resource.layout', $variables)->render();
    }
}
