<?php

namespace Arbory\Base\Admin\Form;

use Arbory\Base\Admin\Form\Widgets\Controls;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Panels\FormPanel;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Admin\Layout\PageInterface;
use Arbory\Base\Admin\Layout\AbstractLayout;
use Arbory\Base\Admin\Layout\WrappableInterface;
use Arbory\Base\Admin\Layout\FormLayoutInterface;
use Arbory\Base\Admin\Layout\Transformers\WrapTransformer;
use Arbory\Base\Admin\Layout\Transformers\AppendTransformer;

class Layout extends AbstractLayout implements FormLayoutInterface
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
                ? $this->form->getModule()->url('edit', [$this->form->getModel()->getKey()])
                : $this->form->getModule()->url('create')
        );

        return $breadcrumbs;
    }

    public function url(string $route, array $parameters = []): string
    {
        return $this->form->getModule()->url($route, $parameters);
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return $this
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
        $this->controls = new Controls(new Tools(), $this->url('index'));

        return $this;
    }

    public function contents($content)
    {
        /**
         * @var WrappableInterface
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

    public function getControls(): Controls
    {
        return $this->controls;
    }

    public function setControls(Controls $controls): self
    {
        $this->controls = $controls;

        return $this;
    }
}
