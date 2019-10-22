<?php

namespace Arbory\Base\Admin\Form;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Builder
 * @package Arbory\Base\Admin\Form
 */
class Builder implements Renderable
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * Builder constructor.
     * @param Form $form
     */
    public function __construct( Form $form )
    {
        $this->form = $form;
    }

    /**
     * @param $route
     * @param array $parameters
     * @param bool $absolute
     * @return string
     */
    public function url( $route, $parameters = [], $absolute = true )
    {
        return $this->form->getModule()->url( $route, $parameters, $absolute );
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
                $this->url( 'dialog', [ 'dialog' => 'toolbox', 'id' => $this->form->getModel()->getKey() ], false )
            )->render();
        }

        return Html::header( [
            Html::h1( $this->form->getTitle() ),
            Html::div( $toolbox )->addClass( 'extras toolbox-wrap' )
        ] );
    }

    /**
     * @return Element
     */
    protected function footer()
    {
        $primary = Html::div()->addClass( 'primary' );
        $secondary = Html::div()->addClass( 'secondary' );

        $primary->append(
            Button::create( 'save_and_return', true )
                ->type( 'submit', 'secondary' )
                ->withIcon( 'check' )
                ->disableOnSubmit()
                ->title( trans( 'arbory::resources.save_and_return' ) )
        );

        $primary->append(
            Button::create( 'save', true )
                ->type( 'submit', 'primary' )
                ->withIcon( 'check' )
                ->disableOnSubmit()
                ->title( trans( 'arbory::resources.save' ) )
        );

        $secondary->append(
            Link::create( $this->url( 'index', [], false ) )
                ->asButton( 'secondary' )
                ->withIcon( 'caret-left' )
                ->title( trans( 'arbory::resources.back_to_list' ) )
        );

        $footerTools = Html::div( [
            $primary,
            $secondary
        ] )->addClass( 'tools' );

        return Html::footer( $footerTools )->addClass( 'main' );
    }

    /**
     * @return Element
     */
    protected function form()
    {
        $form = Html::form()->addAttributes( [
            'id' => 'edit-resource',
            'class' => 'edit-resource',
            'novalidate' => 'novalidate',
            'enctype' => 'multipart/form-data',
            'accept-charset' => 'UTF-8',
            'method' => 'post',
            'action' => $this->form->getAction(),
            'data-remote' => 'true',
            'data-remote-validation' => 'true',
            'data-type' => 'json',
        ] );

        $form->append( csrf_field() );

        if( $this->form->getModel()->getKey() )
        {
            $form->append( Html::input()->setName( '_method' )->setType( 'hidden' )->setValue( 'PUT' ) );
        }

        return $form;
    }

    /**
     * @return \Arbory\Base\Admin\Widgets\Breadcrumbs
     */
    protected function breadcrumbs()
    {
        $breadcrumbs = $this->form->getModule()->breadcrumbs();

        $breadcrumbs->addItem(
            $this->form->getTitle(),
            $this->form->getModel()->getKey()
                ? $this->form->getModule()->url( 'edit', $this->form->getModel()->getKey(), false )
                : $this->form->getModule()->url( 'create', [], false )
        );

        return $breadcrumbs;
    }

    /**
     * @return Content
     */
    public function render()
    {
        $content = Html::div()->addClass( 'body' );

        $content->append( $this->form->fields()->render() );

        return new Content( [
            Html::header( [
                $this->breadcrumbs(),
            ] ),
            Html::section(
                $this->form()
                    ->append( $this->header() )
                    ->append( $content )
                    ->append( $this->footer() )
            )
        ] );
    }

}
