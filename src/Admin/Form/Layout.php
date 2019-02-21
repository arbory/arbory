<?php


namespace Arbory\Base\Admin\Form;


use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Layout\AbstractLayout;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Arbory\Base\Admin\Layout\LayoutInterface;
use Arbory\Base\Admin\Layout\Body;
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

    public function breadcrumbs(Breadcrumbs $breadcrumbs): Breadcrumbs
    {
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

    public function build()
    {
        $this->use(
            new AppendTransformer(
                new Widgets\Controls(new Tools(), $this->url('index'))
            )
        );

        $this->use(
            new WrapTransformer(
                (new FormPanel())->setForm($this->form)
            )
        );
    }
}