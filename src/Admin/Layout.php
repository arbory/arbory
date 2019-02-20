<?php

namespace Arbory\Base\Admin;

use Arbory\Base\Admin\Layout\AbstractLayout;
use Arbory\Base\Admin\Layout\LayoutInterface;
use Arbory\Base\Admin\Layout\Body;
use Arbory\Base\Admin\Layout\Transformers\AppendTransformer;
use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Closure;
use Arbory\Base\Admin\Layout\Row;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

/**
 * Class Layout
 * @package Arbory\Base\Admin
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
        return (string)$this->render();
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
     * @param $breadcrumbs
     *
     * @return $this
     */
    public function breadcrumbs($breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;

        return $this;
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
//        $this->use(function (Wrappable $wrappable, $next) {
//            $wrappable->prepend(
//                Html::header([
//                                 $this->getBreadcrumbs(),
//                             ]));

//            return $next($wrappable);
//        });

        $this->use(new AppendTransformer(new Content($this->rows->all())));
    }

    /**
     * @param                      $content
     * @param LayoutInterface|null $root
     *
     * @return string
     * @throws \Throwable
     */
    public function contents($content)
    {
        $variables = [
            'content'   => $content,
            'bodyClass' => $this->bodyClass,
        ];

        return view('arbory::controllers.resource.layout', $variables)->render();
    }

    /**
     * @return Breadcrumbs
     */
    public function getBreadcrumbs(): ?Breadcrumbs
    {
        return $this->breadcrumbs;
    }
}
