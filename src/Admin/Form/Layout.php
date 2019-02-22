<?php


namespace Arbory\Base\Admin\Form;


use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Layout\AbstractLayout;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Arbory\Base\Admin\Layout\LayoutInterface;
use Arbory\Base\Admin\Layout\Body;
use Arbory\Base\Admin\Layout\PageInterface;
use Arbory\Base\Admin\Layout\Transformers\AppendTransformer;
use Arbory\Base\Admin\Layout\Transformers\WrapTransformer;
use Arbory\Base\Admin\Layout\WrappableInterface;
use Arbory\Base\Admin\Panels\FormPanel;
use Arbory\Base\Admin\Panels\Renderer;
use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Closure;

class Layout extends AbstractLayout implements LayoutInterface
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Form\Widgets\Controls
     */
    protected $controls;

    public function breadcrumbs(): ?Breadcrumbs
    {
        $breadcrumbs = $this->form->getModule()->breadcrumbs();

        $breadcrumbs->addItem(
            $this->form->getTitle(),
            $this->form->getModel()->getKey()
                ? $this->form->getModule()->url('edit', $this->form->getModel()->getKey())
                : $this->form->getModule()->url('create')
        );

        return $breadcrumbs;
    }

    /**
     * @param       $route
     * @param array $parameters
     *
     * @return string
     */
    public function url($route, $parameters = [])
    {
        return $this->form->getModule()->url($route, $parameters);
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
        $this->controls = new Widgets\Controls(new Tools(), $this->url('index'));

        return $this;
    }

    public function contents($content)
    {
        /**
         * @var $renderer WrappableInterface
         */
        $renderer = $this->form->getRenderer();

        $renderer->setContent($content);

        return $renderer;
    }

    public function applyToPage(PageInterface $page)
    {
        $page->setBreadcrumbs($this->breadcrumbs());
    }

    public function build()
    {
        $this->use(
            new AppendTransformer(
                $this->controls
            )
        );

        $this->use(
            new WrapTransformer(
                (new FormPanel())->setForm($this->form)
            )
        );
    }

    /**
     * @return Widgets\Controls
     */
    public function getControls(): Widgets\Controls
    {
        return $this->controls;
    }

    /**
     * @param Widgets\Controls $controls
     *
     * @return Layout
     */
    public function setControls(Widgets\Controls $controls): self
    {
        $this->controls = $controls;

        return $this;
    }
}