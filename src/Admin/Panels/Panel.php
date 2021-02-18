<?php

namespace Arbory\Base\Admin\Panels;

use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Admin\Traits\Renderable;
use Arbory\Base\Admin\Navigator\Navigator;
use Arbory\Base\Admin\Layout\WrappableInterface;
use Arbory\Base\Admin\Navigator\NavigableInterface;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;
use Illuminate\Contracts\Support\Renderable as RenderableInterface;

class Panel implements PanelInterface, WrappableInterface, NavigableInterface
{
    use Renderable;
    use HasRenderOptions;

    /**
     * @var mixed
     */
    protected $content;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var Button[]
     */
    protected $buttons = [];

    /**
     * @var Toolbox
     */
    protected $toolbox;

    protected $navigable = true;

    public function __construct(RenderableInterface $renderer = null)
    {
        $this->renderer = $renderer ?: new Renderer($this);
        $this->toolbox = new Toolbox(null);
    }

    /**
     * @param  Toolbox  $toolbox
     *
     * @return Toolbox
     */
    public function toolbox(Toolbox $toolbox): Toolbox
    {
        return $this->toolbox;
    }

    /**
     * @return Button[]
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param  mixed  $content
     *
     * @return Panel
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param  mixed  $title
     *
     * @return Panel
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param  mixed  $buttons
     *
     * @return Panel
     */
    public function setButtons($buttons): self
    {
        $this->buttons = $buttons;

        return $this;
    }

    /**
     * @param $name
     * @param $url
     *
     * @return Panel
     */
    public function addToolbox($name, $url)
    {
        if (! $this->toolbox->getMenu()) {
            $this->toolbox->setMenu(new ToolboxMenu(null));
        }

        $this->toolbox->getMenu()->add($name, $url);

        return $this;
    }

    /**
     * @param  Button  $button
     *
     * @return $this
     */
    public function addButton(Button $button)
    {
        $this->buttons[] = $button;

        return $this;
    }

    /**
     * Build the panel.
     */
    public function build()
    {
    }

    public function render()
    {
        $this->build();

        return $this->renderer->render();
    }

    /**
     * @return mixed
     */
    public function getToolbox(): ?Toolbox
    {
        if ($this->toolbox === null) {
            $this->toolbox = new Toolbox();
        }

        return $this->toolbox;
    }

    /**
     * @param  Navigator  $navigator
     *
     * @return void
     */
    public function navigator(Navigator $navigator)
    {
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @return bool
     */
    public function isNavigable(): bool
    {
        return $this->navigable;
    }

    /**
     * @param  bool  $navigable
     *
     * @return Panel
     */
    public function setNavigable(bool $navigable): self
    {
        $this->navigable = $navigable;

        return $this;
    }
}
