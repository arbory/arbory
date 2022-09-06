<?php

namespace Arbory\Base\Admin;

use Throwable;
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
     * @param  Closure|null  $callback
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
    public function __toString(): string
    {
        return (string) $this->render();
    }

    /**
     * @return Layout
     */
    public function body(mixed $content)
    {
        return $this->row($content);
    }

    /**
     * @param $content
     * @return $this
     */
    public function row($content)
    {
        $this->rows->push($this->createRow($content));

        return $this;
    }

    /**
     * @param $content
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
     * @return Layout
     */
    public function bodyClass($class)
    {
        $this->bodyClass = $class;

        return $this;
    }

    public function build(): void
    {
        $this->use(new AppendTransformer(new Content($this->rows->all())));
    }

    /**
     * @throws Throwable
     */
    public function contents(mixed $content): mixed
    {
        $variables = [
            'content' => $content,
            'bodyClass' => $this->bodyClass,
        ];

        return view('arbory::controllers.resource.layout', $variables)->render();
    }
}
