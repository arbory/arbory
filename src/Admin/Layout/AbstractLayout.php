<?php

namespace Arbory\Base\Admin\Layout;

use Closure;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Admin\Traits\EventDispatcher;

abstract class AbstractLayout
{
    use EventDispatcher;

    const EVENT_APPLY = 'apply';
    const EVENT_RENDER = 'render';

    const SLOTS = [];

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
     * Returns defined slots.
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
     * Executes every time before render.
     *
     * @return mixed
     */
    abstract public function build();

    /**
     * Executes when the layout is applied.
     *
     * @param PageInterface $page
     */
    public function applyToPage(PageInterface $page)
    {
    }

    /**
     * Renders the layout in its transformed state.
     *
     * @return Content
     */
    public function render()
    {
        // Build layout
        $this->build();

        // Transforms the layout content based on the "used" layouts
        $content = $this->transform(
            new Body($this->manager()->getPage(), $this)
        )->render($this->getContent());

        return new Content([
            $this->contents($content),
        ]);
    }

    public function apply(Body $body, Closure $next, array ...$parameters)
    {
        $this->trigger('apply', $body);

        $body->wrap(
            function ($content) use ($body) {
                $this->applyToPage($body->getPage());
                $this->setContent($content);

                return $this->render();
            },
            $this->manager()->getPage()
        );

        return $next($body);
    }

    /**
     * Adds an transformer to the layout.
     *
     * @param LayoutInterface|string $layout
     *
     * @return $this
     */
    public function use($layout): LayoutResolver
    {
        $resolver = new LayoutResolver(app(), $layout);

        $this->layouts[] = $layout;

        return $resolver;
    }

    /**
     * Transform the content.
     *
     * @param $content
     *
     * @return mixed
     */
    public function transform($content)
    {
        if (count($this->getPipes())) {
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
     * Transformer pipeline.
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
            ->through($this->getPipes());
    }

    /**
     * Set inner content of the layout.
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
     * Returns layout content without any transformation.
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return LayoutInterface[]
     */
    protected function getPipes(): array
    {
        return $this->layouts;
    }

    /**
     * @return LayoutManager
     */
    public function manager(): LayoutManager
    {
        return app(LayoutManager::class);
    }
}
