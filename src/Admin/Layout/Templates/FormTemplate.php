<?php


namespace Arbory\Base\Admin\Layout\Templates;


use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;

class FormTemplate implements TemplateInterface
{
    /**
     * @var \Arbory\Base\Admin\Form
     */
    protected $form;

    protected $sections = [];

    public function form(FormTemplate $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return \Arbory\Base\Admin\Widgets\Breadcrumbs
     */
    public function breadcrumbs():Breadcrumbs
    {
        $breadcrumbs = $this->form->getModule()->breadcrumbs();

        $breadcrumbs->addItem(
            $this->form->getTitle(),
            $this->form->getModel()->getKey()
                ? $this->form->getModule()->url( 'edit', $this->form->getModel()->getKey() )
                : $this->form->getModule()->url( 'create' )
        );

        return $breadcrumbs;
    }

    public function sections(): Collection
    {
        $sections = new Collection($this->sections);

        $sections->put('header', [$this, 'header']);
        $sections->put('content', [$this->form, 'render']);
        $sections->put('footer', []);

        return $sections;
    }

    public function compose():array
    {
        return [
            'header',
            'content',
            'footer'
        ];
    }


    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    protected function header()
    {
        $toolbox = null;

        if( $this->form->getModel()->getKey() )
        {
            $toolbox = Toolbox::create(
                $this->url( 'dialog', [ 'dialog' => 'toolbox', 'id' => $this->form->getModel()->getKey() ] )
            )->render();
        }

        return Html::header( [
            Html::h1( $this->form->getTitle() ),
            Html::div( $toolbox )->addClass( 'extras toolbox-wrap' )
        ] );
    }

    public function content( $content )
    {
        $this->sections['content'] = $content;

        return $this;
    }
}