<?php

namespace CubeSystems\Leaf\Admin\Form;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Widgets\Button;
use CubeSystems\Leaf\Admin\Widgets\Link;
use CubeSystems\Leaf\Admin\Tools\Toolbox;
use CubeSystems\Leaf\Html\Elements\Content;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

/**
 * Class Builder
 * @package CubeSystems\Leaf\Admin\Form
 */
class Builder
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var string
     */
    protected $action;

    /**
     * Builder constructor.
     * @param Form $form
     */
    public function __construct( Form $form )
    {
        $this->form = $form;
    }

    /**
     * @param $action
     * @return $this
     */
    public function setAction( $action )
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        if( $this->action )
        {
            return $this->action;
        }

        if( $this->form->getModel()->getKey() )
        {
            return $this->url( 'update', $this->form->getModel()->getKey() );
        }

        return $this->url( 'store' );
    }

    /**
     * @param $route
     * @param array $parameters
     * @return string
     */
    public function url( $route, $parameters = [] )
    {
        return $this->form->getModule()->url( $route, $parameters );
    }

    /**
     * @return \CubeSystems\Leaf\Html\Elements\Element
     */
    protected function header()
    {
        return Html::header( [
            Html::h1( $this->form->getTitle() ),
            Html::div(
                Toolbox::create(
                    $this->url( 'dialog', [ 'dialog' => 'toolbox', 'id' => $this->form->getModel()->getKey() ] )
                )->render()
            )->addClass( 'extras toolbox-wrap' )
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
            Button::create()
                ->type( 'submit', 'primary' )
                ->withIcon( 'check' )
                ->disableOnSubmit()
                ->title( trans( 'leaf::resources.save' ) )
        );

        $secondary->append(
            Link::create( $this->url( 'index' ) )
                ->asButton( 'secondary' )
                ->withIcon( 'caret-left' )
                ->title( trans( 'leaf::resources.back_to_list' ) )
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
            'action' => $this->getAction(),
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
     * @return \CubeSystems\Leaf\Admin\Widgets\Breadcrumbs
     */
    protected function breadcrumbs()
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

    /**
     * @return Content
     */
    public function render()
    {
        $content = Html::div()->addClass( 'body' );

        foreach( $this->form->fields() as $field )
        {
            $content->append( $field->render() );
        }

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
