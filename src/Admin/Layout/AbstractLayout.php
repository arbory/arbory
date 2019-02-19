<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Html\Elements\Content;
use Closure;
use Illuminate\Pipeline\Pipeline;

abstract class AbstractLayout
{
    /**
     * @var Slot
     */
    protected $root;

    /**
     * @var LayoutInterface[]
     */
    protected $layouts = [];

    protected $pipeline;

    protected $slots;

    protected $content;

    /**
     * @param      $name
     * @param null $content
     *
     * @return Slot
     */
    public function slot($name, $content = null)
    {
        if ($this->root === null) {
            $this->root = new Slot('root');
        }

        if (func_num_args() === 1) {
            return $this->root->getChild($name);
        }

        return $this->root->setChild($name, $content);
    }

    public function slots()
    {
        if ($this->root === null) {
            return collect();
        }

        return $this->root->children();
    }

    abstract function build();

    abstract function contents($content);

    public function render()
    {
        $this->build();

        $contents = new Content();

        $content = $this->transform(
            new Body($this)
        )->render($this->getContent());

        $contents->push($this->contents($content));

        return $contents;
    }

    public function apply(Body $body, Closure $next, array ...$parameters)
    {
        $body->wrap(
            function ($content) {
                $this->setContent($content);

                return $this->render();
            }
        );

        return $next($body);
    }

    public function content()
    {

    }

    public function setWrapper($wrapper)
    {
        $this->wrapper = $wrapper;
    }

    /**
     * @param LayoutInterface|string $layout
     *
     * @return $this
     */
    public function use($layout)
    {
        $this->layouts[] = $layout;

        return $this;
    }

    /**
     * Transform the content
     *
     * @param $content
     *
     * @return mixed
     */
    public function transform($content)
    {
        if (count($this->layouts)) {
            return $this->pipeline()
                        ->send($content)
                        ->then(
                            function ($content) {
                                return $content;
                            }
                        );
        }

        return $content;
    }

    public function pipeline(): Pipeline
    {
        if ($this->pipeline === null) {
            $this->pipeline = new Pipeline(app());
        }

        return $this->pipeline
            ->via('apply')
            ->through($this->layouts);
    }


    public function setContent($content): LayoutInterface
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

}