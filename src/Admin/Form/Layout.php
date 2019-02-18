<?php


namespace Arbory\Base\Admin\Form;


use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Layout\AbstractLayout;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Arbory\Base\Admin\Layout\LayoutInterface;
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

    protected $panels;

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
     * @return \Arbory\Base\Html\Elements\Element
     */
    public function header()
    {
        $toolbox = null;

        if ($this->form->getModel()->getKey()) {
            $toolbox = Toolbox::create(
                $this->url('dialog', ['dialog' => 'toolbox', 'id' => $this->form->getModel()->getKey()])
            )->render();
        }

        return Html::header(
            [
                Html::h1($this->form->getTitle()),
                Html::div($toolbox)->addClass('extras toolbox-wrap'),
            ]
        );
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

    public function build()
    {
        $this->slot('header', [$this, 'header']);
        $this->slot('footer', new Widgets\Controls(new Tools(), $this->url('index')));
    }

    public function setContent($content): LayoutInterface
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->form->fields()->render();
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string                              `
     */
    public function render()
    {
        $this->build();

        return new Content(
            [
                Html::section(
                    [
                        $this->slot('header'),
                        $this->getContent(),
                        $this->slot('footer'),
                    ]
                ),
            ]
        );
    }
}