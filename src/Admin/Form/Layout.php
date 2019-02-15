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

    /**
     * @var Tools
     */
    protected $tools;

    public function __construct()
    {
        $this->tools = new Tools();
    }

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

        return Html::header([
                                Html::h1($this->form->getTitle()),
                                Html::div($toolbox)->addClass('extras toolbox-wrap'),
                            ]);
    }

    /**
     * @return Element
     */
    public function footer()
    {
        $primary   = $this->tools->getBlock('primary');
        $secondary = $this->tools->getBlock('secondary');

        $primary
            ->push(Button::create('save_and_return', true)
                       ->type('submit', 'secondary')
                       ->withIcon('check')
                       ->disableOnSubmit()
                       ->title(trans('arbory::resources.save_and_return')))
            ->push(Button::create('save', true)
                       ->type('submit', 'primary')
                       ->withIcon('check')
                       ->disableOnSubmit()
                       ->title(trans('arbory::resources.save')));

        $secondary->push(
            Link::create($this->url('index'))
                ->asButton('secondary')
                ->withIcon('caret-left')
                ->title(trans('arbory::resources.back_to_list'))
        );


        return Html::footer($this->tools)->addClass('main');
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
        $this->slot('footer', [$this, 'footer']);
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string                              `
     */
    public function render()
    {
        $this->build();

        return new Content([
                               Html::section(
                                   [
                                       $this->slot('header'),
                                       $this->form->render(),
                                       $this->slot('footer'),
                                   ]
                               ),
                           ]);
    }

    public function apply(Content $content, Closure $next, ...$parameters)
    {


        return $next(new Content(
                         [
                             $content,
                             $this->render(),
                         ]
                     ));
    }
}