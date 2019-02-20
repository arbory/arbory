<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Admin\Traits\EventDispatcher;
use Arbory\Base\Html\Elements\Content;
use Closure;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

abstract class AbstractLayout
{
    use EventDispatcher;


    /**
     * @var Slot
     */
    protected $root;

    /**
     * @var LayoutInterface[]
     */
    protected $layouts = [];

    /**
     * @var Pipeline
     */
    protected $pipeline;

    /**
     * @var Collection|Slot
     */
    protected $slots;

    /**
     * @var mixed
     */
    protected $content;

    /**
     * @param string $name
     * @param mixed  $content
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

    /**
     * Returns defined slots
     *
     * @return \Illuminate\Support\Collection
     */
    public function slots()
    {
        if ($this->root === null) {
            return collect();
        }

        return $this->root->children();
    }

    /**
     * Executes every time before render
     *
     * @return mixed
     */
    abstract function build();

    /**
     * Renders the layout in its transformed state
     *
     * @return Content
     */
    public function render()
    {
        // Build any
        $this->build();

        // Transforms the layout content based on the "used" layouts
        $content = $this->transform(
            new Body($this)
        )->render($this->getContent());

        return new Content([
            $this->contents($content, $this),
        ]);
    }

    public function apply(Body $body, Closure $next, array ...$parameters)
    {
        $this->trigger('apply', $body);

        $body->wrap(
            function ($content) {
                $this->setContent($content);

                return $this->render();
            }
        );

        return $next($body);
    }

    /**
     * Adds an transformer to the layout
     *
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

    /**
     * Transformer pipeline
     *
     * @return Pipeline
     */
    public function pipeline(): Pipeline
    {
        if ($this->pipeline === null) {
            $this->pipeline = new Pipeline(app());
        }

        return $this->pipeline
            ->via('apply')
            ->through($this->layouts);
    }

    /**
     * Set inner content of the layout
     *
     * @param mixed $content
     *
     * @return LayoutInterface
     */
    public function setContent($content): LayoutInterface
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Returns layout content without any transformation
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

}